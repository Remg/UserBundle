<?php

namespace Remg\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Remg\UserBundle\Entity\User;
use Remg\UserBundle\Form\UserPasswordRequestType;
use Remg\UserBundle\Form\UserPasswordResetType;

/**
 * @Route("/resetting")
 */
class ResettingController extends Controller
{
    /**
     * @Route("", name="user_resetting_request")
     * @Method({"GET", "POST"})
     */
    public function requestAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserPasswordRequestType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->get('user')->getData();

            if ($user->isPasswordRequestNonExpired()) {
                return $this->render('RemgUserBundle:resetting:passwordAlreadyRequested.html.twig', [
                    'email' => $user->getObfuscatedEmail(),
                ]);
            }

            if (null === $user->getConfirmationToken()) {
                // 4) Generate user token
                $token = $this->get('remg_user.token_generator')->generateToken();
                $user->setConfirmationToken($token);
            }

            $user->setPasswordRequestedAt(new \DateTime());

            // 5) update the user
            $this->getDoctrine()->getManager()->flush();

            // 6) send reset link email
            $message = \Swift_Message::newInstance()
                ->setSubject('Password reset')
                ->setFrom('remi.gardien99@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView('RemgUserBundle:resetting:email.html.twig', [
                        'user' => $user,
                        'confirmation_url' => $this->generateUrl(
                            'user_resetting_reset',
                            ['confirmationToken' => $user->getConfirmationToken()],
                            UrlGeneratorInterface::ABSOLUTE_URL
                        )
                    ]),
                    'text/html'
                );

            $this->get('mailer')->send($message);

            // 7) store email in session to display the confirmation message
            $this->get('session')->set('user_resetting_confirmation_email', $user->getObfuscatedEmail());

            return $this->redirectToRoute('user_resetting_check_email');
        }

        return $this->render('RemgUserBundle:resetting:request.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/check-email", name="user_resetting_check_email")
     * @Method("GET")
     */
    public function checkEmailAction()
    {
        $email = $this->get('session')->get('user_resetting_confirmation_email');

        if (null === $email) {
            return $this->redirectToRoute('user_resetting_request');
        }

        $this->get('session')->remove('user_resetting_confirmation_email');

        return $this->render('RemgUserBundle:resetting:check_email.html.twig', [
            'email' => $email,
        ]);
    }

    /**
     * @Route("/reset/{confirmationToken}", name="user_resetting_reset")
     * @Method({"GET", "POST"})
     */
    public function resetAction(Request $request, User $user)
    {
        if (!$user->isPasswordRequestNonExpired()) {
            $this->addFlash('resetting.reset.flash.already_requested');

            return $this->redirectToRoute('user_resetting_request');
        }

        $form = $this->createForm(UserPasswordResetType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user
                // reset user confirmation token
                ->setConfirmationToken(null)
                ->setPasswordRequestedAt(null);

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'resetting.reset.flash.success');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('RemgUserBundle:resetting:reset.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}