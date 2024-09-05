<?php
namespace App\Controller;
use App\Entity\Borrowing;
use App\Entity\User;
use App\Repository\BookRepository;
use App\Repository\BorrowingRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BorrowingController extends AbstractController
{
    #[Route('/borrowings', name: 'borrowing_list')]
    public function index(BorrowingRepository $borrowingRepository): JsonResponse
    {
        $borrowings = $borrowingRepository->findAll();

        if (count($borrowings) == 0) {
            return $this->json('No Borrowings available in the system');
        }

        foreach ($borrowings as $borrowing) {
            $borrowingArr[] = $borrowing->toArray();
        }

        return $this->json($borrowingArr);
    }

    #[Route('/deleteallborrowings', name: 'borrowings_delete')]
    public function deleteAll(BorrowingRepository $borrowingRepository): JsonResponse
    {
        $res = $borrowingRepository->deleteAllRecords();
        return $this->json($res . " Records deleted permanently");
    }

}
