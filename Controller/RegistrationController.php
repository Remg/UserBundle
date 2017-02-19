<?php

namespace Remg\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Remg\UserBundle\Entity\User;
use Remg\UserBundle\Form\UserRegistrationType;

/**
 * @Route("/register")
 */
class RegistrationController extends Controller
{
    /**
     * @Route("", name="user_registration_register")
     * @Method({"GET", "POST"})
     */
    public function registerAction(Request $request)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserRegistrationType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // 3) Generate user token
            $token = $this->get('remg_user.token_generator')->generateToken();
            $user->setConfirmationToken($token);

            // 4) persist new user
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // 5) send confirmation email
            $this->get('remg_user.mailer.registration')->sendConfirmationEmail($user);

            // 6) store flash message and mail address
            $this->addFlash('success', 'User created successfully.');
            $this->get('session')->set('user_registration_confirmation_email', $user->getEmail());

            return $this->redirectToRoute('user_registration_check_email');
        }

        return $this->render('RemgUserBundle:registration:register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/check-email", name="user_registration_check_email")
     * @Method("GET")
     */
    public function checkEmailAction()
    {
        $email = $this->get('session')->get('user_registration_confirmation_email');

        if (empty($email)) {
            return $this->redirectToRoute('user_registration_register');
        }

        $this->get('session')->remove('user_registration_confirmation_email');

        $user = $this->get('remg_user.user_repository')->findOneByEmail($email);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with email "%s" does not exist', $email));
        }

        return $this->render('RemgUserBundle:registration:check_email.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/confirm/{confirmationToken}", name="user_registration_confirm")
     * @Method("GET")
     */
    public function confirmAction(Request $request, User $user = null)
    {
        if (null === $user) {
            throw new NotFoundHttpException('No user found with this confirmation token.');
        }

        $user
            // reset user confirmation token
            ->setConfirmationToken(null)
            // set user active
            ->setActive(true);

        // save the user
        $this->getDoctrine()->getManager()->flush();

        return $this->render('RemgUserBundle:registration:confirm.html.twig', [
            'user' => $user
        ]);

    }
}