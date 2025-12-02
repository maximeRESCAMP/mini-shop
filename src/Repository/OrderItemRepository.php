<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<OrderItem>
 */
class OrderItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,private readonly PaginatorInterface $paginator)
    {
        parent::__construct($registry, OrderItem::class);
    }

    public function paginateOrderItem(Order $order,int $page, int $limit=1): PaginationInterface
    {

        return $this->paginator->paginate($this->createQueryBuilder('oi')
            ->innerJoin('oi.product','p')
            ->andWhere('oi.userOrder = :userOrder')
            ->setParameter('userOrder',$order)
            ,$page,$limit,[
                false,'sortFieldAllowList'=>[
                    'p.name','oi.quantity','oi.unitPrice','oi.total'
                ]
            ]
        );
    }


//    /**
//     * @return OrderItem[] Returns an array of OrderItem objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?OrderItem
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
