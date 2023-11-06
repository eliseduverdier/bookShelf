<?php

namespace App\Controller;

use App\Repository\Book\ReadBookRepository;
use App\Repository\Book\WriteBookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Service\Attribute\Required;

class SaveController extends AbstractController
{
    #[Required]
    public WriteBookRepository $bookRepository;

    #[Route('/book', name: 'save_book', methods: ['POST'])]
    public function index(
        Request $request,
    ): Response
    {
        $this->bookRepository->save($request->request, $this->getUser());

        return $this->redirect('/');
    }
}
