<?php

namespace App\Repository\Book;

use App\Entity\Book;
use App\Entity\Type;
use App\Entity\User;
use App\Repository\NoteRepository;
use App\Repository\TypeRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Service\Attribute\Required;

class ReadBookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function findForUser(
        ?UserInterface $user,
        array $order = [],
        array $filter = [],
    )
    {
        $query = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('b')
            ->addSelect('CASE WHEN b.finished_at IS NULL THEN 1 else 0 END AS HIDDEN currentlyReading') // sort "currently reading" first
            ->from(Book::class, 'b')
            ->where('b.user = :user')->setParameter('user', $user)
            ->andWhere('b.deletedAt IS NULL');

        if ($filter) {
            foreach ($filter as $field => $value) {
                $query->andWhere($query->expr()->eq("b.$field", ':author'))
                    ->setParameter('author', $value);
            }
        };

        if ($order) {
            foreach ($order as $field => $way) {
                $query->addOrderBy("b.$field", $way);
            }
        }

        $query
            ->addOrderBy('currentlyReading', 'desc')
            ->addOrderBy('b.finished_at', 'desc');

        return $query->getQuery()->execute();
    }

    public function getMostReadAuthors(User $user): array
    {
        $query = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('b.author')
            ->addSelect('COUNT(b.title) AS countbooks')
            ->from(Book::class, 'b')
            ->where('b.user = :user')->setParameter('user', $user)
            ->groupBy('b.author')
            ->having('countbooks > 1')
            ->orderBy('countbooks', 'desc');

        return $query->getQuery()->getResult();
    }

    public function getBookCountByType(User $user): array
    {
        $query = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('t.name')
            ->addSelect('COUNT(b.title) AS countbooks')
            ->from(Book::class, 'b')
            ->join('b.type', 't')
            ->where('b.user = :user')->setParameter('user', $user)
            ->groupBy('b.type')
            ->orderBy('countbooks', 'desc');
        return $query->getQuery()->getResult();
    }

    public function getBookCountByNote(User $user): array
    {
        $query = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('n.legend')
            ->addSelect('COUNT(b.title) AS countbooks')
            ->from(Book::class, 'b')
            ->join('b.note', 'n')
            ->where('b.user = :user')->setParameter('user', $user)
            ->groupBy('b.note')
            ->orderBy('countbooks', 'desc');

        return $query->getQuery()->getResult();
    }
}
