<?php

namespace App\Repository;

use App\Entity\CartItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CartItem>
 */
class CartItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CartItem::class);
    }

        /**
         * @return CartItem[] Returns an array of CartItem objects
         */
        public function findIdByUser($user): array
        {
            return $this->createQueryBuilder('ci')
                ->select('IDENTITY(ci.product)')
                ->andWhere('ci.user = :user')
                ->setParameter('user', $user)
                ->orderBy('ci.id', 'ASC')
                ->getQuery()
                ->getSingleColumnResult()
            ;
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
