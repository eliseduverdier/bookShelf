<?php

namespace App\Controller;

use App\Repository\Book\ReadBookRepository;
use App\Repository\NoteRepository;
use App\Repository\TypeRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Service\Attribute\Required;

class UsersController extends AbstractController
{
    #[Required]
    public ReadBookRepository $bookRepository;
    #[Required]
    public UserRepository $userRepository;

    #[Route('/users', name: 'users')]
    public function index(Request $request): Response
    {
        return $this->render('user/list.html.twig', [
            'users' => $this->userRepository->findAll(),
        ]);
    }

    #[Route('/user/{username}', name: 'list_books_for_user')]
    public function listForUser(Request $request, string $username): Response
    {
        $user = $this->userRepository->findOneBy(['username' => $username]);

        $allBooks =  $this->bookRepository->findForUser($user);
        $privateBooks = array_filter($allBooks, fn ($book) => $book->is_private === false);

        return $this->render('list-for-user.html.twig', [
            'user' => $user,
            'books' => $privateBooks,
            'authorsStatistics' => $this->bookRepository->getMostReadAuthors($user),
            'notesStatistics' => $this->bookRepository->getBookCountByNote($user),
            'typesStatistics' => $this->bookRepository->getBookCountByType($user),
            'notes' => $this->bookRepository->getBookCountByNote($user),
            'types' => $this->bookRepository->getBookCountByType($user),
        ]);
    }
}
