<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Books;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Repository\BooksRepository;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;


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

        $Books = new Books();

        $form = $this->createFormBuilder($Books)
        ->add('BokNamn', TextType::class, ['label' => 'Book Name'])
        ->add('ISBN', IntegerType::class, ['label' => 'ISBN'])
        ->add('forfattare', TextType::class, ['label' => 'Author'])
        ->add('bild', FileType::class, [
            'label' => 'Book Cover (Image file)',
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new File([
                    'maxSize' => '2M',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                        'image/gif',
                    ],
                    'mimeTypesMessage' => 'Please upload a valid image file (JPEG, PNG, GIF)',
                ])
            ],
        ])
            ->getForm();
 
        $form->handleRequest($request);
        $bildFile = "daj";
        if ($form->isSubmitted() && $form->isValid()) {
            $bildFile = $form->get('bild')->getData();

            if ($bildFile) {
                $originalFilename = pathinfo($bildFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $bildFile->guessExtension();
                try {
                    $bildFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                    $this->logger->info('File uploaded to: ' . $this->getParameter('images_directory') . '/' . $newFilename);
                } catch (FileException $e) {
                    $this->logger->error('File upload error: ' . $e->getMessage());
                    throw new \Exception('File upload error: ' . $e->getMessage());
                }

                $Books->setBild($newFilename);

            }

            $entityManager->persist($Books);
            $entityManager->flush();
            return $this->redirectToRoute('library_show_all_books');


        }

        return $this->render('library/new.html.twig', [
            'form' => $form->createView(),
            'bild' => $bildFile
        ]);
    
    }

    #[Route('/library/showall', name: 'library_show_all_books')]
    public function showAllLibrarybooks(
        BooksRepository $booksRepository
    ): Response {
        $books = $booksRepository
            ->findAll();
            return $this->render('library/show.html.twig', [
                'books' => $books,
            ]);
    }

    #[Route('/library/showone/{id}', name: 'library_show_one_book')]
    public function showOneLibrarybooks(
        BooksRepository $booksRepository,
        int $id ,
    ): Response {
        $books = $booksRepository
            ->find($id);

            return $this->render('library/showone.html.twig', [
                'books' => $books,
            ]);
    }

    #[Route('/library/edit/{id}', name: 'library_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id ,BooksRepository $booksRepository): Response
    {
        $book = $booksRepository->find($id);
        if (!$book) {
            throw $this->createNotFoundException('The book does not exist');
        }

        $form = $this->createFormBuilder($book)
        ->add('BokNamn', TextType::class, ['label' => 'Book Name'])
        ->add('ISBN', IntegerType::class, ['label' => 'ISBN'])
        ->add('forfattare', TextType::class, ['label' => 'Author'])
        ->add('bild', FileType::class, [
            'label' => 'Book Cover (Image file)',
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new File([
                    'maxSize' => '2M',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                        'image/gif',
                    ],
                    'mimeTypesMessage' => 'Please upload a valid image file (JPEG, PNG, GIF)',
                ])
            ],
        ])
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bildFile = $form->get('bild')->getData();
            if ($bildFile) {
                $originalFilename = pathinfo($bildFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $bildFile->guessExtension();
                try {
                    $bildFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('File upload error: ' . $e->getMessage());
                }
                $book->setBild($newFilename);
            }

            $this->entityManager->flush();

            return $this->redirectToRoute('library_show_all_books');
        }

        return $this->render('library/edit.html.twig', [
            'form' => $form->createView(),
            'book' => $book,
        ]);
    }

    #[Route('/library/delete/{id}', name: 'library_delete', methods: ['POST'])]
    public function delete(Request $request, int $id ,BooksRepository $booksRepository): Response
    {
        $book = $booksRepository->find($id);
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
            BooksRepository $booksRepository
        ): Response {
            $book = $booksRepository
                ->findAll();
    
            return $this->json($book);
        }
    

    #[Route('/api/library/book/{isbn}', name: 'api_library_book', methods: ['GET'])]
    public function getBookByISBN(BooksRepository $booksRepository, string $isbn): Response {
        $book = $booksRepository
            ->findOneBy(['ISBN' => $isbn]);

        return $this->json($book);
    
    }
}
