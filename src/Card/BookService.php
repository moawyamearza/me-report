<?php

namespace App\Card;

use App\Entity\Booklib;
use Doctrine\ORM\EntityManagerInterface;

class BookService {
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function saveBook(Booklib $book): void {
        $this->entityManager->persist($book);
        $this->entityManager->flush();
    }
}
