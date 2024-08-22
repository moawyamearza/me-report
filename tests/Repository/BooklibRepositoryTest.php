<?php

namespace App\Tests\Repository;

use App\Entity\Booklib;
use App\Repository\BooklibRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BooklibRepositoryTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager = null;
    private ?BooklibRepository $repository = null;

    protected function setUp(): void
    {
        self::bootKernel();

        // Get the EntityManager from the kernel container
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        if ($this->entityManager === null) {
            throw new \RuntimeException('EntityManagerInterface could not be retrieved from the container.');
        }
        
        $this->repository = $this->entityManager->getRepository(Booklib::class);
        if ($this->repository === null) {
            throw new \RuntimeException('Repository for Booklib could not be retrieved.');
        }

        // Setup schema for tests
        $schemaTool = new SchemaTool($this->entityManager);
        $classes = $this->entityManager->getMetadataFactory()->getAllMetadata();
        
        // Drop and create schema
        $schemaTool->dropSchema($classes);
        $schemaTool->createSchema($classes);
    }

    protected function tearDown(): void
    {
        if ($this->entityManager !== null) {
            $schemaTool = new SchemaTool($this->entityManager);
            $classes = $this->entityManager->getMetadataFactory()->getAllMetadata();
            
            // Drop schema
            $schemaTool->dropSchema($classes);

            $this->entityManager->close();
            $this->entityManager = null;
        }

        $this->repository = null;
    }

    public function testFind(): void
    {
        $booklib = new Booklib();
        $booklib->setBookname('Test Book');
        $booklib->setIsbn('1234567890');
        $booklib->setWriter('Test Author');
        $booklib->setImage('test_image.jpg');
        
        $this->entityManager->persist($booklib);
        $this->entityManager->flush();

        $id = $booklib->getId();
        $foundBooklib = $this->repository->find($id);

        $this->assertNotNull($foundBooklib);
        $this->assertEquals('Test Book', $foundBooklib->getBookname());
        $this->assertEquals('1234567890', $foundBooklib->getIsbn());
        $this->assertEquals('Test Author', $foundBooklib->getWriter());
        $this->assertEquals('test_image.jpg', $foundBooklib->getImage());
    }

    public function testFindOneBy(): void
    {
        $booklib = new Booklib();
        $booklib->setBookname('Unique Book');
        $booklib->setIsbn('0987654321');
        $booklib->setWriter('Unique Author');
        $booklib->setImage('unique_image.jpg');
        
        $this->entityManager->persist($booklib);
        $this->entityManager->flush();

        $foundBooklib = $this->repository->findOneBy(['bookname' => 'Unique Book']);

        $this->assertNotNull($foundBooklib);
        $this->assertEquals('Unique Book', $foundBooklib->getBookname());
        $this->assertEquals('0987654321', $foundBooklib->getIsbn());
        $this->assertEquals('Unique Author', $foundBooklib->getWriter());
        $this->assertEquals('unique_image.jpg', $foundBooklib->getImage());
    }

    public function testFindAll(): void
    {
        $booklib1 = new Booklib();
        $booklib1->setBookname('Book 1');
        $booklib1->setIsbn('1111111111');
        $booklib1->setWriter('Author 1');
        $booklib1->setImage('book1_image.jpg');
        $this->entityManager->persist($booklib1);

        $booklib2 = new Booklib();
        $booklib2->setBookname('Book 2');
        $booklib2->setIsbn('2222222222');
        $booklib2->setWriter('Author 2');
        $booklib2->setImage('book2_image.jpg');
        $this->entityManager->persist($booklib2);

        $this->entityManager->flush();

        $allBooks = $this->repository->findAll();

        $this->assertCount(2, $allBooks);
        $this->assertEquals('Book 1', $allBooks[0]->getBookname());
        $this->assertEquals('Book 2', $allBooks[1]->getBookname());
    }

    public function testFindBy(): void
    {
        $booklib1 = new Booklib();
        $booklib1->setBookname('Book A');
        $booklib1->setIsbn('3333333333');
        $booklib1->setWriter('Author A');
        $booklib1->setImage('booka_image.jpg');
        $this->entityManager->persist($booklib1);

        $booklib2 = new Booklib();
        $booklib2->setBookname('Book B');
        $booklib2->setIsbn('4444444444');
        $booklib2->setWriter('Author B');
        $booklib2->setImage('bookb_image.jpg');
        $this->entityManager->persist($booklib2);

        $booklib3 = new Booklib();
        $booklib3->setBookname('Book A');
        $booklib3->setIsbn('5555555555');
        $booklib3->setWriter('Author C');
        $booklib3->setImage('booka2_image.jpg');
        $this->entityManager->persist($booklib3);

        $this->entityManager->flush();

        $books = $this->repository->findBy(['bookname' => 'Book A']);

        $this->assertCount(2, $books);
        $this->assertEquals('Book A', $books[0]->getBookname());
        $this->assertEquals('Book A', $books[1]->getBookname());
    }
}
