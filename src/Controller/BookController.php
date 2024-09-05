<?php
namespace App\Controller;
use App\Entity\Book;
use App\Entity\User;
use App\Repository\BookRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookController extends AbstractController
{
    #[Route('/books', name: 'book_list')]
    public function index(BookRepository $bookRepository)
    {
        $books = $bookRepository->findAll();

        if (count($books) == 0) {
            return $this->json('No Books available in the system');
        }

        foreach ($books as $book) {
            $booksArr[] = $book->toArray();
        }

        return $this->json($booksArr);
    }

    #[Route('/book/create/{name}/{author}', name: 'create_book')]
    public function createUser(EntityManagerInterface $entityManager, BookRepository $bookRepository, $name, $author): Response
    {
        //Check if the user is already available with the system
        $book = $bookRepository->findOneBy(['name' => $name, 'author' => $author]);

        if ($book != null) {
            return new Response('Book already exist with the name  ' . $name . ' and author ' . $author);
        }

        //If given Book is not available register the book
        $book = new Book();
        $book->setName($name);
        $book->setAuthor($author);


        // tell Doctrine you want to (eventually) save the User (no queries yet)
        $entityManager->persist($book);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new book with id ' . $book->getId());
    }

    #[Route('/book/{id}', name: 'book_show')]
    public function show(BookRepository $bookRepository, int $id): Response
    {
        $book = $bookRepository->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No Book found for id ' . $id
            );
        }

        return new Response('Check out this great book: ' . $book->getName());

    }

    /**
     * Delete complete list of Books
     */
    #[Route('/deleteallbooks', name: 'books_delete')]
    public function deleteAll(BookRepository $bookRepository): JsonResponse
    {
        $res = $bookRepository->deleteAllRecords();

        return $this->json($res . " Records deleted permanently");
    }


}
