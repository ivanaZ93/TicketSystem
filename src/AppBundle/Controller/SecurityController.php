<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
	/**
     * Akcija za logiranje korisnika
     *
     * @Route("/login", name="AppBundle_Security_login")
     */
    public function loginAction(Request $request)
    {
    	$authenticationUtils = $this->get('security.authentication_utils');
    	$error = $authenticationUtils->getLastAuthenticationError();
    	$lastUsername = $authenticationUtils->getLastUsername();
    	return $this->render('AppBundle:Security:login.html.twig', [
        	'last_username' => $lastUsername,
        	'error' => $error,
    	]);
    }
}
