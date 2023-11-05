<?php

namespace App\Controller;

use App\Repository\Book\ReadBookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Service\Attribute\Required;

class StatisticsController extends AbstractController
{

    #[Required]
    public ReadBookRepository $bookRepository;

    #[Route('/statistics', name: 'statistics')]
    public function index(Request $request): Response
    {
        $authors = $this->bookRepository->getMostReadAuthors($this->getUser());
        $notes = $this->bookRepository->getBookCountByNote($this->getUser());
        $types = $this->bookRepository->getBookCountByType($this->getUser());

        return $this->render('statistics.html.twig', [
            'currentUser' => $this->getUser(),
            'authors' => $authors,
            'notes' => $notes,
            'types' => $types,
        ]);
    }
}
