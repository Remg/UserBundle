<?php

namespace Remg\UserBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Remg\UserBundle\Entity\User;
use Remg\UserBundle\Form\UserType;
use Remg\UserBundle\Form\UserFilterType;

/**
 * @Route("/admin/user")
 */
class UserController extends Controller
{
    /**
     * Lists all user entities.
     *
     * @Route("", name="admin_user_index")
     * @Method("GET")
     */
    public function indexAction()
    {
    	$filterForm = $this->createForm(UserFilterType::class, null, array(
    		'action' => $this->generateUrl('admin_user_index'),
    		'method' => 'GET'
    	));

    	$pagination = $this
            ->get('remg_admin_paginator')
            ->paginate(User::class, $filterForm);

    	return $this->render('RemgUserBundle:admin:user/index.html.twig', array(
    		'pagination' => $pagination,
    		'filter_form' => $filterForm->createView()
    	));
    }

    /**
     * Creates a new user entity.
     *
     * @Route("/new", name="admin_user_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $user = new User();

        $user = $userManager->createUser();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $this->addFlash('success', 'user.new.success');

                return $this->redirectToRoute('admin_user_show', array('id' => $user->getId()));
            }

            $this->addFlash('error', 'user.new.error');
        }

        return $this->render('RemgUserBundle:admin:user/new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a user entity.
     *
     * @Route("/{id}", name="admin_user_show")
     * @Method("GET")
     */
    public function showAction(User $user)
    {
        $deleteForm = $this->createDeleteForm($user);

        return $this->render('RemgUserBundle:admin:user/show.html.twig', array(
            'user' => $user,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/{id}/edit", name="admin_user_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, User $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm(UserType::class, $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted()) {
            if ($editForm->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash('success', 'user.edit.success');

                return $this->redirectToRoute('admin_user_edit', array('id' => $user->getId()));
            }

            $this->addFlash('error', 'user.edit.error');
        }

        return $this->render('RemgUserBundle:admin:user/edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a user entity.
     *
     * @Route("/{id}", name="admin_user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, User $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($user);
                $em->flush();

                $this->addFlash('success', 'user.delete.success');
            } else {
                $this->addFlash('error', 'user.delete.error');
            }
        }

        return $this->redirectToRoute('admin_user_index');
    }

    /**
     * Creates a form to delete a user entity.
     *
     * @param User $user The user entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
