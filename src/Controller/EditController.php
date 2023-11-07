<?php

namespace App\Controller;

use App\Repository\Book\ReadBookRepository;
use App\Repository\Book\WriteBookRepository;
use App\Repository\NoteRepository;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Service\Attribute\Required;

class EditController extends AbstractController
{
    #[Required]
    public ReadBookRepository $readBookRepository;
    #[Required]
    public WriteBookRepository $writeBookRepository;
    #[Required]
    public EntityManagerInterface $em;
    #[Required]
    public TypeRepository $typeRepository;
    #[Required]
    public NoteRepository $noteRepository;

    #[Route('/book/{slug}/edit', name: 'edit_book', methods: ['POST'])]
    public function index(Request $request, string $slug): Response
    {
        $book = $this->readBookRepository->findOneBy(['slug' => $slug]);
        if ($this->getUser() && $book->user !== $this->getUser()) {
            $this->redirectToRoute('list_books');
        }

        try {
            $this->writeBookRepository->edit($book, $request->request);
            return new RedirectResponse('/');
        } catch (\Exception $e) {
            return $this->render('error.html.twig', [
                'error' => "Error while editing « $slug » : {$e->getMessage()}"
            ]);
        }
    }
}
