<?php

namespace AppBundle\Controller;

use AppBundle\Entity\SearchParameters;
use AppBundle\Entity\Ticket;
use AppBundle\Entity\User;
use AppBundle\Form\Type\CommentType;
use AppBundle\Form\Type\SearchParametersType;
use AppBundle\Form\Type\TicketType;
use AppBundle\Repository\TicketRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TicketController extends Controller
{
    /**
     * Akcija za otvaranje određenog radnog naloga
     *
     * Provjerava se također je li korisnik administrator, jer
     * ako jest onda će se pojavit dodatna opcija da obriše nalog.
     *
     * @Route("/ticket/{ticketId}", name="AppBundle_Ticket_ticket")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function ticketAction(int $ticketId)
    {
        $auth_checker = $this->get('security.authorization_checker');
        $token = $this->get('security.token_storage')->getToken();
        $user = $token->getUser();
        $isRoleAdmin = $auth_checker->isGranted('ROLE_ADMIN');

        $ticketRepository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Ticket');
        $ticket = $ticketRepository->findOneById($ticketId);
        
        if (!$ticket) {
            throw $this->createNotFoundException(sprintf('Ticket with id "%s" not found.', $ticketId));
        }
        return $this->render('AppBundle:Ticket:ticket.html.twig', [
            'ticket' => $ticket,
            'ticketId' => $ticketId,
            'isRoleAdmin' => $isRoleAdmin,
        ]);
    }

    /**
     * Akcija za prikaz svih radnih naloga
     *
     * @Route("/tickets", name="AppBundle_Ticket_ticketList")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Method("GET")
     */
    public function ticketListAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $form = $this->get('form.factory')->createNamed(null, SearchParametersType::class, new SearchParameters());
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $tickets = $manager->getRepository('AppBundle:Ticket')->findByParameters($form->getData());
        } else {
            $tickets = $manager->getRepository('AppBundle:Ticket')->findAll();
        }
        return $this->render('AppBundle:Ticket:ticketList.html.twig', [
            'tickets' => $tickets,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Akcija za dodavanje radnog naloga
     *
     * @Route("/tickets/add", name="AppBundle_Ticket_ticketAdd")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function ticketAddAction(Request $request)
    {
        $ticket = new Ticket();
        $ticket->setTicketAuthor($this->getUser());

        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);
        $insert = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $ticket = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($ticket);
            $entityManager->flush();

            $insert = true;
        }

        return $this->render('AppBundle:Ticket:ticketAdd.html.twig', [
            'form' => $form->createView(),
            'insert' => $insert
        ]);

    }
    
    /**
     * Akcija za mijenjanje već postojećeg radnog naloga
     *
     * @Route("/ticket/{ticketId}/edit", name="AppBundle_Ticket_ticketEdit")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Method({"GET", "POST"})
     */
    public function ticketEditAction(int $ticketId, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $ticket = $em->getRepository('AppBundle:Ticket')->find($ticketId);

        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if (!$ticket) {
            throw $this->createNotFoundException(
                'No ticket found for id '.$ticketId
            );
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $editPriority = $form->getData();
            $em->persist($editPriority);
            $editStatus = $form->getData();
            $em->persist($editStatus);
            $em->flush();

            return $this->redirectToRoute('AppBundle_Ticket_ticketList');
        }

        return $this->render('AppBundle:Ticket:ticketEdit.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Brisanje ticketa, može samo admin
     *
     * @Route("admin/ticket/{ticketId}/delete", name="AppBundle_Ticket_ticketDelete")
     */
    public function ticketDeleteAction(Request $request, int $ticketId)
    {
       $em = $this->getDoctrine()->getEntityManager();
       $ticket = $em->getRepository('AppBundle:Ticket')->find($ticketId);

       $em->remove($ticket);
       $em->flush();   

       return $this->redirectToRoute('AppBundle_Ticket_ticketList');
    }

    /**
     * Akcija koja hvata naloge trenutno ulogiranog korisnika
     *
     * @Route("/myprofile/{userId}", name="AppBundle_Ticket_ticketUserList")
     */
    public function ticketUserAction($userId)
    {
        $tickets = $this->getDoctrine()->getRepository('AppBundle:Ticket')
            ->findByTicketAuthor($userId);

        if ($userId == ($this->getUser()->getId())){
            return $this->render('AppBundle:Ticket:ticketUserList.html.twig', [
                'tickets' => $tickets
            ]);
        }
        throw $this->createNotFoundException();
    }
}
