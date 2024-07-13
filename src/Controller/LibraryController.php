<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Booklib;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Repository\BooklibRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Validator\Constraints\File;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;


class LibraryController extends AbstractController
{
    private $logger;
    private $entityManager;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
    }

    #[Route('/library', name: 'app_library')]
    public function index(): Response
    {
        return $this->render('library/index.html.twig', [
            'controller_name' => 'LibraryController',
        ]);
    }
    #[Route('/library/new', name: 'book_new')]
    public function new(
        Request $request,
        ManagerRegistry $doctrine
    ): Response {
        $entityManager = $doctrine->getManager();

        $Books = new Booklib();

        $form = $this->createFormBuilder($Books)
        ->add('bookname', TextType::class, ['label' => 'Book Name'])
        ->add('isbn',TextType::class, ['label' => 'ISBN'])       
        ->add('writer', TextType::class, ['label' => 'Author'])
        ->add('image', UrlType::class, ['label' => 'Book Cover'])
            ->getForm();
 
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($Books);
            $entityManager->flush();
            return $this->redirectToRoute('library_show_all_books');
        }

        return $this->render('library/new.html.twig', [
            'form' => $form->createView(),
        ]);
    
    }

    #[Route('/library/showall', name: 'library_show_all_books')]
    public function showAllLibrarybooks(
        BooklibRepository $booklibRepository
    ): Response {
        $books = $booklibRepository
            ->findAll();
            return $this->render('library/show.html.twig', [
                'books' => $books,
            ]);
    }

    #[Route('/library/showone/{id}', name: 'library_show_one_book')]
    public function showOneLibrarybooks(
        BooklibRepository $booklibRepository,
        int $id ,
    ): Response {
        $books = $booklibRepository
            ->find($id);

            return $this->render('library/showone.html.twig', [
                'books' => $books,
            ]);
    }

    #[Route('/library/edit/{id}', name: 'library_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id ,BooklibRepository $booklibRepository, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $book = $booklibRepository->find($id);
        if (!$book) {
            throw $this->createNotFoundException('The book does not exist');
        }

        $form = $this->createFormBuilder($book)
        ->add('bookname', TextType::class, ['label' => 'Book Name'])
        ->add('isbn',TextType::class, ['label' => 'ISBN'])        
        ->add('writer', TextType::class, ['label' => 'Author'])
        ->add('image', UrlType::class, ['label' => 'Book Cover'])
            ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($book);
                $entityManager->flush();
                return $this->redirectToRoute('library_show_all_books');
            }
    
            return $this->render('library/new.html.twig', [
                'form' => $form->createView(),
            ]);
        
        }

    #[Route('/library/delete/{id}', name: 'library_delete', methods: ['POST'])]
    public function delete(Request $request, int $id ,BooklibRepository $booklibRepository): Response
    {
        $book = $booklibRepository->find($id);
        if (!$book) {
            throw $this->createNotFoundException('The book does not exist');
        }
        
        if ($this->isCsrfTokenValid('delete' . $book->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($book);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('library_show_all_books');
    }
    
    #[Route('/api/library/books', name: 'api_library_books', methods: ['GET'])]
    public function getAllBooks(
            BooklibRepository $booklibRepository
        ): Response {
            $book = $booklibRepository
                ->findAll();
    
            return $this->json($book);
        }
    

    #[Route('/api/library/book/{isbn}', name: 'api_library_book', methods: ['GET'])]
    public function getBookByISBN(BooklibRepository $booklibRepository, string $isbn): Response {
        $book = $booklibRepository
            ->findOneBy(['isbn' => $isbn]);

        return $this->json($book);
    
    }
}
