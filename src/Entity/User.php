<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    private $user;


    // #[OneToMany(targetEntity: Borrowing::class, mappedBy: 'user')]
    private $borrowing;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function borrows(Book $book)
    {

        $bookId = $book->getId();

        $borrowing = new Borrowing($this->id, $bookId);

        return $borrowing;
    }


    // public function setBorrowing(Book $book)
    // {
    //     $this->borrowing->setBook = $book;

    //     return $this;
    // }

    // public function getBorrowing()
    // {
    //     return $this->borrowing;
    // }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
