<?php

namespace App\Repository\Book;

use App\Entity\Book;
use App\Entity\User;
use App\Repository\NoteRepository;
use App\Repository\TypeRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Contracts\Service\Attribute\Required;

class WriteBookRepository extends ServiceEntityRepository
{
    #[Required]
    public TypeRepository $typeRepository;
    #[Required]
    public NoteRepository $noteRepository;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function edit(?Book $book, ParameterBag $parameters): void
    {
        $book->title = $parameters->get('title');
        $book->author = $parameters->get('author');

        $book->finished_at = $parameters->get('finished_at')
            ? new \DateTime($parameters->get('finished_at'))
            : null;
        $book->summary = $parameters->get('summary');
        $book->abandonned_at = $parameters->get('abandonned');
        $book->private_book = $parameters->get('private_book', 0);
        $book->private_summary = $parameters->get('private_summary', 0);

        $type = $this->typeRepository->find($parameters->get('type'));
        $book->type = $type;
        $note = $this->noteRepository->find($parameters->get('note'));
        $book->note = $note;

        $this->getEntityManager()->persist($book);
        $this->getEntityManager()->flush();
    }

    public function save(InputBag $parameters, User $user)
    {
        $type = $this->typeRepository->find($parameters->get('type'));
        $note = $this->noteRepository->find($parameters->get('note'));
        $book = new Book(
            $user,
            $parameters->get('title'),
            $parameters->get('author'),
            $type,
            $note,
            $parameters->get('finished_at') ? new \DateTime($parameters->get('finished_at')) : null,
        );
        $this->getEntityManager()->persist($book);
        $this->getEntityManager()->flush();
    }

    public function delete(?Book $book): void
    {
        $book->deletedAt = new \DateTime();
        $this->getEntityManager()->persist($book);
        $this->getEntityManager()->flush();
    }
}
