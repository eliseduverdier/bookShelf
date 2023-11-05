<?php

namespace App\Controller;

use App\Repository\Book\ReadBookRepository;
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
    public ReadBookRepository $bookRepository;
    #[Required]
    public EntityManagerInterface $em;
    #[Required]
    public TypeRepository $typeRepository;
    #[Required]
    public NoteRepository $noteRepository;

    #[Route('/book/{slug}', name: 'edit_book', methods: ['POST'])]
    public function index(Request $request, string $slug): Response
    {
        $book = $this->bookRepository->findOneBy(['slug' => $slug]);

        try {
            $this->bookRepository->edit($book, $request->request);
            return new RedirectResponse('/');
        } catch (\Exception $e) {
            return $this->render('error.html.twig', [
                'currentUser' => $this->getUser(),
                'error' => "Error while editing « $slug » : {$e->getMessage()}"
            ]);
        }
    }
}
