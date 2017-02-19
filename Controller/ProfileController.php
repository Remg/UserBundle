<?php

namespace Remg\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Remg\UserBundle\Entity\User;
use Remg\UserBundle\Form\UserPasswordResetType;

/**
 * @Route("/profile")
 */
class ProfileController extends Controller
{
    /**
     * @Route("", name="user_profile_show")
     * @Method("GET")
     */
    public function showAction()
    {
        return $this->render('RemgUserBundle:profile:show.html.twig', [
            'user' => $this->getCurrentUser(),
        ]);
    }

    /**
     * @Route("/edit", name="user_profile_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request)
    {
        $user = $this->getCurrentUser();

        $form = $this->createForm(UserProfileType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'user.profile.edit.flash.success');

            return $this->redirectToRoute('user_profile_show');
        }

        return $this->render('RemgUserBundle:profile:edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("change-password", name="user_profile_change_password")
     * @Method({"GET", "POST"})
     */
    public function changePasswordAction(Request $request)
    {
        $user = $this->getCurrentUser();

        $form = $this->createForm(UserChangePasswordType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user
                ->setPassword(
                    $this->get('security.password_encoder')
                    ->encodePassword($user, $user->getPlainPassword())
                )
                ->setPlainPassword(null);

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'user.profile.change_password.flash.success');

            return $this->redirectToRoute('user_profile_show');
        }

        return $this->render('RemgUserBundle:profile:change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Returns the current User entity.
     */
    public function getCurrentUser()
    {
        return $this->get('security.context')->getToken()->getUser();
    }
}