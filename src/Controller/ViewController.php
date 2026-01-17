<?php

namespace App\Controller;

use App\Repository\Book\ReadBookRepository;
use App\Repository\NoteRepository;
use App\Repository\TypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Service\Attribute\Required;

class ViewController extends AbstractController
{
    #[Required]
    public ReadBookRepository $bookRepository;
    #[Required]
    public TypeRepository $typeRepository;
    #[Required]
    public NoteRepository $noteRepository;

    #[Route('/book/{slug}', name: 'view_book', methods: ['GET'])]
    public function view(Request $request, string $slug): Response
    {
        $book = $this->bookRepository->findOneBy(['slug' => $slug]);

        if (!$book) {
            return $this->render('error.html.twig', [
                'error' => "No book found with slug « $slug »",
            ]);
        }

        return $this->render('view.html.twig', [
            'book' => $book,
        ]);
    }
}
