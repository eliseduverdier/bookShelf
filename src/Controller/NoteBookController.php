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

class NoteBookController extends AbstractController
{
    #[Required]
    public ReadBookRepository $bookRepository;
    #[Required]
    public WriteBookRepository $writeBookRepository;

    #[Route('/note-book/{slug}', name: 'note_book', methods: ['GET'])]
    public function finish(Request $request, string $slug): Response
    {
        $note = $request->query->get('note');
        /** @var Book $book */
        $book = $this->bookRepository->findOneBy(['slug' => $slug]);

        if ($book->user !== $this->getUser()) {
            $this->redirectToRoute('list');
        }
        $this->writeBookRepository->note($book, $note);

        return $this->redirectToRoute('list_books');
    }
}
