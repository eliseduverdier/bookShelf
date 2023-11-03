<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Service\Attribute\Required;

class ViewController extends AbstractController
{
    #[Required]
    public BookRepository $bookRepository;

    #[Route('/book/{slug}', name: 'view_books')]
    public function list(Request $request, string $slug): Response
    {
        $book = $this->bookRepository->findOneBy(['slug' => $slug]);

        return $this->render('view.html.twig', [
            'currentUser' => $this->getUser(),
            'book' => $book
        ]);
    }
}
