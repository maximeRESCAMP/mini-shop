<?php

namespace App\Repository;

use App\Entity\CartItem;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<CartItem>
 */
class CartItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private readonly PaginatorInterface $paginator)
    {
        parent::__construct($registry, CartItem::class);
    }

    public function paginateCartItem(User $user, int $page, int $limit ): PaginationInterface
    {
        return $this->paginator->paginate($this->createQueryBuilder('ci')->innerJoin('ci.product','p')->andWhere('ci.user = :user')->setParameter('user',$user), $page, $limit, [true, 'sortFieldAllowList' => ['p.name', 'p.price', 'ci.quantity']]);
    }

    /**
     * @return CartItem[] Returns an array of CartItem objects
     */
    public function findProductIdsByUser(User $user): array
    {
        return $this->createQueryBuilder('ci')
            ->select('IDENTITY(ci.product)')
            ->andWhere('ci.user = :user')
            ->setParameter('user', $user)
            ->orderBy('ci.id', 'ASC')
            ->getQuery()
            ->getSingleColumnResult();
    }

    //    public function findOneBySomeField($value): ?CartItem
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
