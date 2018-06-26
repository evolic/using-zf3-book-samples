<?php
namespace Books\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;

/**
 * A Author entity class
 *
 * @ORM\Entity()
 * @ORM\Table(name="authors")
 */
class Author
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
    private $first_name;

    /**
     * @ORM\Column(type="string", length=56)
     */
    private $last_name;

    /**
     * @ORM\Column(type="date");
     */
    private $birth_date;

    /**
     * @ORM\Column(type="date");
     * @var null|\DateTime
     */
    private $death_date;

    /**
     * One Author has Many Books.
     *
     * @ORM\OneToMany(targetEntity="Books\Entity\Book", mappedBy="author")
     */
    private $books;

    /**
     * Book constructor.
     */
    public function __construct()
    {
        $this->books = new ArrayCollection();
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
    public function getFirstName(): string
    {
        return $this->first_name;
    }

    /**
     * @param  string  $first_name
     * @return Author
     */
    public function setFirstName(string $first_name): Author
    {
        $this->first_name = $first_name;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->last_name;
    }

    /**
     * @param string $last_name
     */
    public function setLastName(string $last_name): Author
    {
        $this->last_name = $last_name;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getBirthDate(): DateTime
    {
        return $this->birth_date;
    }

    /**
     * @param  DateTime  $birth_date
     * @return Author
     */
    public function setBirthDate(DateTime $birth_date): Author
    {
        $this->birth_date = $birth_date;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDeathDate(): ?DateTime
    {
        return $this->death_date;
    }

    /**
     * @param  DateTime  $death_date
     * @return Author
     */
    public function setDeathDate(?DateTime $death_date): Author
    {
        $this->death_date = $death_date;

        return $this;
    }

    /**
     * Return author books
     *
     * @return PersistentCollection
     */
    public function getBooks(): PersistentCollection
    {
        return $this->books;
    }
}
