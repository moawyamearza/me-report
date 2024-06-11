<?php

namespace App\Entity;

use App\Repository\BooksRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BooksRepository::class)]
class Books
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $BokNamn = null;

    #[ORM\Column(nullable: true)]
    private ?int $ISBN = null;

    #[ORM\Column(length: 255)]
    private ?string $forfattare = null;

    #[ORM\Column(type: Types::BLOB, nullable: true)]
    private $bild = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBokNamn(): ?string
    {
        return $this->BokNamn;
    }

    public function setBokNamn(?string $BokNamn): static
    {
        $this->BokNamn = $BokNamn;

        return $this;
    }

    public function getISBN(): ?int
    {
        return $this->ISBN;
    }

    public function setISBN(?int $ISBN): static
    {
        $this->ISBN = $ISBN;

        return $this;
    }

    public function getForfattare(): ?string
    {
        return $this->forfattare;
    }

    public function setForfattare(string $forfattare): static
    {
        $this->forfattare = $forfattare;

        return $this;
    }

    public function getBild()
    {
        return $this->bild;
    }

    public function setBild($bild): static
    {
        $this->bild = $bild;

        return $this;
    }
}
