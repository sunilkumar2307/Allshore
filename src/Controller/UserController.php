<?php
namespace App\Controller;
use App\Entity\User;
use App\Repository\BookRepository;
use App\Repository\BorrowingRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    /**
     * List all the users available in the system
     */
    #[Route('/users', name: 'user_list')]
    public function index(UserRepository $userRepository): JsonResponse
    {

        $users = $userRepository->findAll();

        if (count($users) == 0) {
            return $this->json('No Users available in the system');
        }

        foreach ($users as $user) {
            $usersArr[] = $user->toArray();
        }

        return $this->json($usersArr);
    }

    /**
     * Controller action to create new user if not exist
     */
    #[Route('/user/create/{name}', name: 'create_user')]
    public function createUser(UserRepository $userRepository, EntityManagerInterface $entityManager, $name): Response
    {
        //Check if the user is already available with the system
        $user = $userRepository->findOneByName($name);

        if ($user != null) {
            return new Response('User already exist with the name  ' . $name);
        }

        //If given user is not available register the user
        $user = new User();
        $user->setName($name);

        // tell Doctrine you want to (eventually) save the User (no queries yet)
        $entityManager->persist($user);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new user with id ' . $user->getId());
    }

    /**
     * Get a pertigular user by given id
     */
    #[Route('/user/{id}', name: 'user_show')]
    public function show(UserRepository $userRepository, int $id): Response
    {
        $user = $userRepository->find($id);

        if (!$user) {
            return new Response('No User Exist in the system with the given Id ' . $id);
        }

        return new Response('See this great user: ' . $user->getName());
    }

    /**
     * Borrow the given Book to given User if all the requirements are fullfilled.
     * Book shoul be exist.
     * User Shoul Exist.
     * Ensure the test includes a scenario where a book cannot be borrowed twice.
     * limiting users to a maximum of five borrowed books 
     */
    #[Route('/barrowbook/{uid}/{bid}', name: 'barrow_book')]
    public function barrowBook(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        BookRepository $bookRepository,
        BorrowingRepository $borrowingRepository,
        int $uid,
        int $bid
    ): Response {

        //get User details
        $user = $userRepository->find($uid);
        //get Book details
        $book = $bookRepository->find($bid);

        //Check whether required Book is a valid one
        if ($book == null) {
            return new Response('No Book exist with the given id ' . $bid);
        }

        //Check whether required user is Exist
        if ($user == null) {
            return new Response('No User exist with the given id ' . $uid);
        }

        //Check whether required book is available for Borrowing
        $book_borrowed = $borrowingRepository->checkAvailability($bid);
        if (count($book_borrowed)) {
            return new Response('Currently Required book is Borrowed by others.
                                 We will notify you if the Book came to availability and you are Eligible to Borrow at that time');
        }

        //check whether User is Eligible to Borrow the book. Since one User can borrow only 5 Books at a time.
        $user_borrowed = $borrowingRepository->getborrowedBooksByUser($uid);
        if (count($user_borrowed) >= 5) {
            return new Response('User is not Eligible to Borrow the book. Since one User can borrow only 5 Books at a time');
        }

        //since all the validations passed for borrowing the required book by the user we can proceed with bowwing the book to User
        $borrowing = $user->borrows($book);
        $entityManager->persist($borrowing);
        $entityManager->flush();

        return new Response('New Borrowing added with provided details');
    }

    /**
     * Checkin Book back if the same user Borrowed
     */
    #[Route('/checkinbook/{uid}/{bid}', name: 'checkin_book')]
    public function checkinBook(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        BookRepository $bookRepository,
        BorrowingRepository $borrowingRepository,
        int $uid,
        int $bid
    ): Response {

        //get User details
        $user = $userRepository->find($uid);
        //get Book details
        $book = $bookRepository->find($bid);

        //Check whether required Book is a valid one
        if ($book == null) {
            return new Response('No Book exist with the given id ' . $bid);
        }

        //Check whether required user is Exist
        if ($user == null) {
            return new Response('No User exist with the given id ' . $uid);
        }

        //Check whether required book was Borrowed by given User
        $book_borrowed = $borrowingRepository->findOneBy(
            ['user_id' => $uid, 'book_id' => $bid, 'checkin_date' => null],
        );

        if (empty($book_borrowed)) {
            return new Response('This Operation is not Possible. Provided Book is not Borrowed by this User.');
        }

        $book_borrowed->setCheckinDate(new \DateTime('now'));

        // tell Doctrine you want to (eventually) save the changes (no queries yet)
        $entityManager->persist($book_borrowed);

        // actually executes the queries
        $entityManager->flush();

        return new Response('Checking details for the Book for the User is successfull updated.');
    }

    /**
     * Delete complete list of Users
     */
    #[Route('/deleteallusers', name: 'users_delete')]
    public function deleteAll(UserRepository $userRepository): JsonResponse
    {
        $res = $userRepository->deleteAllRecords();

        return $this->json($res . " Records deleted permanently");
    }
}
