<?php
namespace Books\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * A Note entity class
 *
 * @ORM\Entity(repositoryClass="Books\Doctrine\Repository\NoteRepository")
 * @ORM\Table(name="notes")
 */
class Note
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=56)
     */
    private $title;


    /**
     * @ORM\Column(type="string")
     */
    private $body;

    /**
     * Many Reviews have One Book.
     *
     * @ORM\ManyToOne(targetEntity="Author", inversedBy="books")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    private $author;

    /**
     * Book constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Return book's author
     *
     * @return Author
     */
    public function getAuthor(): Author
    {
        return $this->author;
    }
}
