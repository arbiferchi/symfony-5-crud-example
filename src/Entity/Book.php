<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author;

    /**
     * @ORM\Column(type="date")
     */
    private $pubYear;

    /**
     * @ORM\OneToMany(targetEntity=Author::class, mappedBy="book")
     */
    private $oneToMany;

    public function __construct()
    {
        $this->oneToMany = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getPubYear(): ?\DateTimeInterface
    {
        return $this->pubYear;
    }

    public function setPubYear(\DateTimeInterface $pubYear): self
    {
        $this->pubYear = $pubYear;

        return $this;
    }

    /**
     * @return Collection|Author[]
     */
    public function getOneToMany(): Collection
    {
        return $this->oneToMany;
    }

    public function addOneToMany(Author $oneToMany): self
    {
        if (!$this->oneToMany->contains($oneToMany)) {
            $this->oneToMany[] = $oneToMany;
            $oneToMany->setBook($this);
        }

        return $this;
    }

    public function removeOneToMany(Author $oneToMany): self
    {
        if ($this->oneToMany->removeElement($oneToMany)) {
            // set the owning side to null (unless already changed)
            if ($oneToMany->getBook() === $this) {
                $oneToMany->setBook(null);
            }
        }

        return $this;
    }
}
