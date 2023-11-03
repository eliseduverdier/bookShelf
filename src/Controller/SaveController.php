<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Repository\NoteRepository;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Service\Attribute\Required;

class SaveController extends AbstractController
{
    #[Required]
    public BookRepository $bookRepository;

    #[Route('/book', name: 'save_book', methods: ['POST'])]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        TypeRepository $typeRepository,
        NoteRepository $noteRepository
    ): Response
    {
        $type = $typeRepository->find($request->request->get('type'));
        $note = $noteRepository->find($request->request->get('note'));
        $book = new Book(
            $this->getUser(),
            $request->request->get('title'),
            $request->request->get('author'),
            $type,
            $note,
            new \DateTime($request->request->get('finished_at')),
        );
        $entityManager->persist($book);
        $entityManager->flush();

        return $this->redirect('/');
    }
}
