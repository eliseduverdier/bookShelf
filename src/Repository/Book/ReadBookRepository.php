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
            // stopped working randomly ?!
            // ->addSelect('CASE WHEN b.finished_at IS NULL THEN 1 else 0 END AS HIDDEN currentlyReading') // sort "currently reading" first
            ->from(Book::class, 'b')
            ->where('b.user = :user')->setParameter('user', $user)
            ->andWhere('b.deletedAt IS NULL');
        if ($filter) {
            foreach ($filter as $field => $value) {
                $query->andWhere($query->expr()->eq("b.$field", ':filter'))
                    ->setParameter('filter', $value);
            }
        };

        if ($order) {
            foreach ($order as $field => $way) {
                $query->addOrderBy("b.$field", $way);
            }
        }

        $query
            ->addOrderBy('b.abandonned_at', 'asc')
            // ->addOrderBy('currentlyReading', 'desc')
            ->addOrderBy('b.finished_at', 'desc');

        $books = $query->getQuery()->execute();

        $currentlyReading = array_filter($books, function (Book $book) {
            return $book->finished_at === null && $book->abandonned_at === null;
        });
        return [...$currentlyReading, ...$books];
    }

    public function getMostReadAuthors(?User $user): array
    {
        $query = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('b.author')
            ->addSelect('COUNT(b.title) AS countbooks')
            ->from(Book::class, 'b')
            ->where('b.user = :user')->setParameter('user', $user)
            ->groupBy('b.author')
            ->having('countbooks > 1')
            ->orderBy('countbooks', 'desc')
            ->setMaxResults(10);;

        return $query->getQuery()->getResult();
    }

    public function getBookCountByType(?User $user): array
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

        $result = $query->getQuery()->getResult();
        $total = array_sum(array_column($result, 'countbooks'));
        $percents = [];
        foreach ($result as $count) {
            $percents[$count['name']] = ceil(($count['countbooks'] * 100) / $total);
        }
        return $percents;
    }

    public function getBookCountByNote(?User $user): array
    {
        $query = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('n.legend, n.name')
            ->addSelect('COUNT(b.title) AS countbooks')
            ->from(Book::class, 'b')
            ->join('b.note', 'n')
            ->where('b.user = :user')->setParameter('user', $user)
            ->groupBy('b.note')
            ->orderBy('n.id', 'desc');

        $result = $query->getQuery()->getResult();
        $total = array_sum(array_column($result, 'countbooks'));
        $percents = [];
        foreach ($result as $count) {
            $percents[$count['legend']] = (int)(($count['countbooks'] * 100) / $total);
        }
        return $percents;
    }

    public function getReadCountByYear(?User $user)
    {
        $query = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('YEAR(b.finished_at) as y, COUNT(b.title) as count')
            ->from(Book::class, 'b')
            ->where('b.user = :user')->setParameter('user', $user)
            ->andWhere('b.finished_at IS NOT NULL')
            ->groupBy('y')
            ->orderBy('y', 'desc')
            ->setMaxResults(10);

        return $query->getQuery()->getResult();
    }
}
