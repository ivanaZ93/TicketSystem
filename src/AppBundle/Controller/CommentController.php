<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Ticket;
use AppBundle\Form\Type\CommentType;
use AppBundle\Form\Type\TicketType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{   
    /**
     * Akcija za dodavanje komentara, isključivo korisnika
     *
     * @Route("/{ticketId}/commentAdd", name="AppBundle_Ticket_commentAdd")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function commentAddAction(Request $request, int $ticketId)
    {
        $em = $this->getDoctrine()->getManager();
        $ticket = $em->getRepository('AppBundle:Ticket')->find($ticketId);

    	$comment = new Comment();
        $comment->setCommentAuthor($this->getUser());
        $comment->setTicket($ticket);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        $insert = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($comment);
            $entityManager->flush();

            $insert = true;
        }

        return $this->render('AppBundle:Ticket:commentAdd.html.twig', [
            'ticketId' => $ticketId,
            'form' => $form->createView(),
            'insert' => $insert
        ]);
    }
}
