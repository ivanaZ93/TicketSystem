<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Klasa koja predstavlja komentare, usera autora i nalog na koji se odnosi
 *
 * @ORM\Entity
 * @ORM\Table(name="comment")
 */
class Comment
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
     * @var Ticket
     *
     * @ORM\ManyToOne(targetEntity="Ticket", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ticket;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="comment.blank")
     * @Assert\Length(
     *     min=5,
     *     minMessage="comment is too short",
     *     max=10000,
     *     maxMessage="comment is too long"
     * )
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $publishedAt;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $commentAuthor;

    public function __construct()
    {
        $this->publishedAt = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTime $publishedAt)
    {
        $this->publishedAt = $publishedAt;
    }

    /**
     * @return User
     */
    public function getCommentAuthor()
    {
        return $this->commentAuthor;
    }

    /**
     * @param User $commentAuthor
     */
    public function setCommentAuthor(User $commentAuthor)
    {
        $this->commentAuthor = $commentAuthor;
    }

    public function getTicket()
    {
        return $this->ticket;
    }

    public function setTicket(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }
}