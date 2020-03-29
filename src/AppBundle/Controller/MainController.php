<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
    /**
     * Akcija za otvaranje naslovnice
     *
     * @Route("/", name="AppBundle_Home_homepage")
     */
    public function homepageAction(Request $request)
    {
        return $this->render('AppBundle:Home:homepage.html.twig');
    }

    /**
     * Akcija za otvaranje stranice s informacijama o projektu
     *
     * @Route("/about", name="AppBundle_Home_about")
     */
    public function aboutAction(Request $request)
    {
        return $this->render('AppBundle:Home:about.html.twig');
    }
}
