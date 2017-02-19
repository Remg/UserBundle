<?php

namespace Remg\UserBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Remg\UserBundle\Entity\Group;
use Remg\UserBundle\Form\GroupType;
use Remg\UserBundle\Form\GroupFilterType;

/**
 * @Route("/admin/group")
 */
class GroupController extends Controller
{
    /**
     * Lists all group entities.
     *
     * @Route("", name="admin_group_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $filterForm = $this->createFilterForm();

    	$pagination = $this
            ->get('remg_admin_paginator')
            ->paginate(Group::class, $filterForm);

    	return $this->render('RemgUserBundle:admin:group/index.html.twig', array(
    		'pagination' => $pagination,
    		'filter_form' => $filterForm->createView()
    	));
    }

    /**
     * Creates a new group entity.
     *
     * @Route("/new", name="admin_group_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $groupManager = $this->get('fos_user.group_manager');

        $group = $groupManager->createGroup('');

        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $groupManager->updateGroup($group, true);

                $this->addFlash('success', 'group.new.success');

                return $this->redirectToRoute('admin_group_show', array('id' => $group->getId()));
            }

            $this->addFlash('error', 'group.new.error');
        }

        return $this->render('RemgUserBundle:admin:group/new.html.twig', array(
            'group' => $group,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a group entity.
     *
     * @Route("/{id}", name="admin_group_show")
     * @Method("GET")
     */
    public function showAction(Group $group)
    {
        $deleteForm = $this->createDeleteForm($group);

        return $this->render('RemgUserBundle:admin:group/show.html.twig', array(
            'group' => $group,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing group entity.
     *
     * @Route("/{id}/edit", name="admin_group_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Group $group)
    {
        $deleteForm = $this->createDeleteForm($group);
        $editForm = $this->createForm(GroupType::class, $group);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted()) {
            if ($editForm->isValid()) {
                $this->get('fos_user.group_manager')->updateGroup($group, true);

                $this->addFlash('success', 'group.edit.success');

                return $this->redirectToRoute('admin_group_edit', array('id' => $group->getId()));
            }

            $this->addFlash('error', 'group.edit.error');
        }

        return $this->render('RemgUserBundle:admin:group/edit.html.twig', array(
            'group' => $group,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a group entity.
     *
     * @Route("/{id}", name="admin_group_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Group $group)
    {
        $form = $this->createDeleteForm($group);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($group);
                $em->flush($group);

                $this->addFlash('success', 'group.delete.success');
            } else {
                $this->addFlash('error', 'group.delete.error');
            }
        }

        return $this->redirectToRoute('admin_group_index');
    }

    /**
     * Creates a form to filter posts entities.
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createFilterForm()
    {
        return $this->createForm(GroupFilterType::class, null, [
            'action' => $this->generateUrl('admin_group_index'),
            'method' => 'GET',
        ]);
    }

    /**
     * Creates a form to delete a group entity.
     *
     * @param Group $group The group entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Group $group)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_group_delete', array('id' => $group->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
