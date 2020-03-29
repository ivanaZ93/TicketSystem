<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
	/**
     * Akcija za prikaz registriranih korisnika (samo administratoru)
     *
     * @Route("/admin/users", name="AppBundle_User_userList")
     * @Method("GET")
     */
    public function userListAction()
    {
        $manager = $this->getDoctrine()->getManager();
        $users = $manager->getRepository('AppBundle:User')->findAll();

        return $this->render('AppBundle:User:userList.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * Brisanje korisnika, može samo admin
     *
     * @Route("admin/user/{userId}/delete", name="AppBundle_User_userDelete")
     */
    public function userDeleteAction(Request $request, int $userId)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository('AppBundle:User')->find($userId);

        $username = $user->getUsername();
        $email = $user->getEmail();

        $this->indexAction($username, $email);

        $em->remove($user);
        $em->flush();   

        return $this->redirectToRoute('AppBundle_User_userList');
    }

    /**
     * Akcija koja će poslati korisniku obavijest da ga je administrator izbrisao
     */
    public function indexAction($username, $email)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('You are deleted')
            ->setFrom('php@example.com')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                    'AppBundle:User:sendMail.html.twig',
                    ['username' => $username]
                ),
                'text/html'
            )
        ;
        $this->get('mailer')->send($message);

        return $this->redirectToRoute('AppBundle_User_userList');
    }
}