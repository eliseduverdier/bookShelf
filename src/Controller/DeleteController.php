<?php

namespace App\Controller;

use App\Repository\Book\ReadBookRepository;
use App\Repository\Book\WriteBookRepository;
use App\Repository\NoteRepository;
use App\Repository\TypeRepository;
use App\Util\PathUtil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Service\Attribute\Required;

class DeleteController extends AbstractController
{
    #[Required]
    public ReadBookRepository $bookRepository;
    #[Required]
    public WriteBookRepository $writeBookRepository;
    #[Required]
    public EntityManagerInterface $em;
    #[Required]
    public TypeRepository $typeRepository;
    #[Required]
    public NoteRepository $noteRepository;

    #[Route('/book/{slug}/delete', name: 'delete_book', methods: ['GET'])]
    public function index(Request $request, string $slug): Response
    {
        $book = $this->bookRepository->findOneBy(['slug' => $slug]);

        try {
            $this->writeBookRepository->delete($book);
            return new RedirectResponse(PathUtil::getRootPath());
        } catch (\Exception $e) {
            return $this->render('error.html.twig', [
                'error' => "Error while deleting « $slug » : {$e->getMessage()}",
            ]);
        }
    }
}
