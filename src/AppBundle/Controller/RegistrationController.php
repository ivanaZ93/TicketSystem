<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\Type\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class RegistrationController extends Controller
{
    /**
     * Akcija za registraciju.
     *
     * Nakon registracije korisnik ostaje ulogiran, umjesto da se treba
     * ponovno ulogirat.
     *
     * @Route("/register", name="AppBundle_Registration_register")
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->get('security.token_storage')->setToken($token);
            $this->get('session')->set('_security_main', serialize($token));

            $username = $user->getUsername();
            $email = $user->getEmail();

            $this->indexAction($username, $email);

            return $this->redirectToRoute('AppBundle_Home_homepage');
        }

        return $this->render(
            'AppBundle:Registration:register.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Akcija za slanje maila prilikom registracije
     */
    public function indexAction(string $username, string $email)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Hello new user')
            ->setFrom('php@example.com')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                    'AppBundle:Registration:sendMail.html.twig',
                    ['username' => $username]
                ),
                'text/html'
            )
        ;
        $this->get('mailer')->send($message);

        return $this->redirectToRoute('AppBundle_Home_homepage');
    }
}
