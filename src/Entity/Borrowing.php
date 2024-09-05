<?php

namespace App\Entity;

use App\Repository\BorrowingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;

#[ORM\Entity(repositoryClass: BorrowingRepository::class)]
class Borrowing
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $user_id = null;

    #[ORM\Column]
    private ?int $book_id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $checkout_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $checkin_date = null;

    // #[ManyToOne(targetEntity: User::class, inversedBy: 'borrowing')]
    // #[JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    // private $user;

    // #[ManyToOne(targetEntity: Book::class)]
    // private $book;


    public function __construct($user_id, $book_id)
    {
        $this->user_id = $user_id;
        $this->book_id = $book_id;
        $this->checkout_date = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getBookId(): ?int
    {
        return $this->book_id;
    }

    public function setBookId(int $book_id): static
    {
        $this->book_id = $book_id;

        return $this;
    }

    public function getCheckoutDate(): ?\DateTimeInterface
    {
        return $this->checkout_date;
    }

    public function setCheckoutDate(\DateTimeInterface $checkout_date): static
    {
        $this->checkout_date = $checkout_date;

        return $this;
    }

    public function getCheckinDate(): ?\DateTimeInterface
    {
        return $this->checkin_date;
    }

    public function setCheckinDate(?\DateTimeInterface $checkin_date): static
    {
        $this->checkin_date = $checkin_date;

        return $this;
    }

    // public function getUser(): User
    // {
    //     return $this->user;
    // }

    // public function setUser(User $user)
    // {
    //     $this->user = $user;

    //     return $this;
    // }


    // public function getBook(): Book
    // {
    //     return $this->book;
    // }

    // public function setBook(Book $book)
    // {
    //     $this->book = $book;

    //     return $this;
    // }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            // 'book' => $this->book->toArray(),
            // 'user' => $this->user->toArray(),
            'book' => $this->book_id,
            'user' => $this->user_id,
            'chckin_date' => $this->checkin_date,
            'checkou_date' => $this->checkout_date,
        ];
    }
}
