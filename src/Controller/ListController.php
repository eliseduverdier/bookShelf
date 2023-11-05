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

class ListController extends AbstractController
{
    #[Required]
    public ReadBookRepository $bookRepository;
    #[Required]
    public TypeRepository $typeRepository;
    #[Required]
    public NoteRepository $noteRepository;

    #[Route('/', name: 'list_books')]
    #[Route('/books', name: 'list_books2')]
    public function index(Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirect('/login');
        }
        $order = $request->query->getIterator()->getArrayCopy();
        $booksForUser = $this->bookRepository->findForUser(
            $this->getUser(),
            $order['order'] ?? [],
            $order['filter'] ?? []
        );

        return $this->render('list.html.twig', [
            'currentUser' => $this->getUser(),
            'books' => $booksForUser,
            'types' => $this->typeRepository->findAll(),
            'notes' => $this->noteRepository->findAll(),
        ]);
    }
}
