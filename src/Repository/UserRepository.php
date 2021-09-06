<?php
namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param array $filter
     * @return int
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countList(array $filter): int
    {
        $qb = $this->getListQuery($filter);

        $qb->select('count(distinct u)');

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param array $filter
     * @param string $order
     * @param int $limit
     * @param int $offset
     * @return ArrayCollection
     */
    public function getList(array $filter, string $order, int $limit, int $offset): ArrayCollection
    {
        $qb = $this->getListQuery($filter);

        $qb
            ->select('u')
            ->orderBy('u.created', $order)
            ->setFirstResult($offset)
            ->setMaxResults($limit)
        ;

        $result = $qb->getQuery()->getResult();

        return new ArrayCollection($result);
    }

    /**
     * @param array $filter
     * @return QueryBuilder
     */
    private function getListQuery(array $filter): QueryBuilder
    {
        $qb = $this
            ->createQueryBuilder('u')
        ;

        if (isset($filter['search']) && $filter['search']) {
            $qb
                ->where(
                    $qb->expr()->orX(
                        $qb->expr()->like('lower(u.name)', ':search'),
                        $qb->expr()->like('lower(u.email)', ':search'),
                    )
                )
                ->setParameter('search',   '%' . mb_strtolower($filter['search']) . '%')
            ;
        }

        if (isset($filter['active']) && $filter['active']) {
            $qb
                ->andWhere(
                    $qb->expr()->isNull('u.deleted')
                )
            ;
        }

        return $qb;
    }
}
