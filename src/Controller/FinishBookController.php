<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\Book\ReadBookRepository;
use App\Repository\Book\WriteBookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Service\Attribute\Required;

class FinishBookController extends AbstractController
{
    #[Required]
    public ReadBookRepository $bookRepository;
    #[Required]
    public WriteBookRepository $writeBookRepository;

    #[Route('/finish-book/{slug}', name: 'finish_book', methods: ['GET'])]
    public function finish(Request $request, string $slug): Response
    {
        /** @var Book $book */
        $book = $this->bookRepository->findOneBy(['slug' => $slug]);

        if ($book->user !== $this->getUser()) {
            $this->redirectToRoute('list');
        }

        $this->writeBookRepository->finish($book, new \DateTime());

        return $this->redirectToRoute('list_books', ['finished' => 1]);
    }
}
