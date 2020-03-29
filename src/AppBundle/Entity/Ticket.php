<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Klasa koja predstavlja radni nalog, autora i komentare
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TicketRepository")
 * @ORM\Table(name="ticket")
 */
class Ticket
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Assert\Length(min=10, minMessage="Content is too short!")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $publishedAt;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ticketAuthor;

    /**
     * @var Comment[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity="Comment",
     *      mappedBy="ticket",
     *      orphanRemoval=true
     * )
     * @ORM\OrderBy({"publishedAt": "ASC"})
     */
    private $comments;

    /**
     * @ORM\Column(name="priority", type="string", columnDefinition="enum('low', 'medium', 'high')")
     */
    private $priority;

    /**
     * @ORM\Column(name="status", type="string", columnDefinition="enum('opened', 'in_process', 'closed')")
     */
    private $status;


    public function __construct()
    {
        $this->publishedAt = new \DateTime();
        $this->comments = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    // public function setPublishedAt(\DateTime $publishedAt)
    // {
    //     $this->publishedAt = $publishedAt;
    // }

    /**
     * @return User
     */
    public function getTicketAuthor()
    {
        return $this->ticketAuthor;
    }

    /**
     * @param User $ticketAuthor
     */
    public function setTicketAuthor(User $ticketAuthor)
    {
        $this->ticketAuthor = $ticketAuthor;
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function addComment(Comment $comment)
    {
        $this->comments->add($comment);
        $comment->setTicket($this);
    }

    public function removeComment(Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

}