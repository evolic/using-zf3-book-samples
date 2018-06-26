<?php
namespace Books\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * A Book entity class
 *
 * @ORM\Entity()
 * @ORM\Table(name="books")
 */
class Book
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
     * @ORM\Column(type="integer");
     */
    private $author_id;

    /**
     * Many Books have One Author.
     *
     * @ORM\ManyToOne(targetEntity="Books\Entity\Author", inversedBy="books")
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
     * @param  string  $title
     * @return Book
     */
    public function setTitle(string $title): Book
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return int
     */
    public function getAuthorId(): int
    {
        return $this->author_id;
    }

    /**
     * @param  string  $author_id
     * @return Book
     */
    public function setAuthorId($author_id): Book
    {
        $this->author_id = (int) $author_id;

        return $this;
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

    /**
     * @param  Author  $author
     * @return Book
     */
    public function setAuthor(Author $author): Book
    {
        $this->author = $author;

        return $this;
    }
}
