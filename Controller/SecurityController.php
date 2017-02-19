<?php

namespace Remg\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Remg\UserBundle\Form\UserLoginType;

class SecurityController extends Controller
{
	/**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
	    $authenticationUtils = $this->get('security.authentication_utils');

    	$form = $this->createForm(UserLoginType::class);

    	$form->handleRequest($request);
    	$form->get('username')->setData($authenticationUtils->getLastUsername());

	    return $this->render('RemgUserBundle:security:login.html.twig', array(
	        'error' => $authenticationUtils->getLastAuthenticationError(),
	        'form' => $form->createView()
	    ));
    }
}