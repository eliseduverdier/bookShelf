<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\Security\Core\User\UserInterface;

class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function edit(?Book $book, ParameterBag $parameters): void
    {
        $book->title = $parameters->get('title');
        $book->author = $parameters->get('author');

        $book->finished_at = new \DateTime($parameters->get('finished_at'));
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

    public function delete(?Book $book): void
    {
        $book->deletedAt = new \DateTime();
        $this->getEntityManager()->persist($book);
        $this->getEntityManager()->flush();
    }

    public function findForUser(?UserInterface $user)
    {
        $query = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('b')
            ->from(Book::class, 'b')
            ->where('b.user = :user')->setParameter('user', $user)
            ->andWhere('b.deletedAt IS NULL');

        return $query->getQuery()->execute();
    }

}
