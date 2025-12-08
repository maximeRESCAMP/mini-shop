<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\User;
use App\Exception\Order\CannotQueryOrderException;
use App\Repository\OrderItemRepository;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

readonly class OrderService
{
    public function __construct(private OrderRepository $orderRepository, private OrderItemRepository $orderItemRepository, private readonly TranslatorInterface $translator)
    {
    }

    /**
     * @throws CannotQueryOrderException
     */
    public function findOrderByUser(User $user): array
    {
        try {
            return $this->orderRepository->findBy(['user' => $user]);

        } catch (\Throwable $exception) {
            throw new CannotQueryOrderException($this->translator->trans(CannotQueryOrderException::$messageQuerry));
        }
    }

    /**
     * @throws CannotQueryOrderException
     */
    public function paginateOrder(User $user, int $page = 1, int $offset = 10): PaginationInterface
    {
        try {
            return $this->orderRepository->paginateOrder($user, $page, $offset);

        } catch (\Throwable $exception) {
            throw new CannotQueryOrderException($this->translator->trans(CannotQueryOrderException::$messageQuerry));
        }
    }

    public function createColumn():array{
        $tabColumn =["created_at","reference","address","total","status"];
        return array_map(fn($column)=>$this->translator->trans('order.list.column.'.$column),$tabColumn);
    }


    /**
     * @throws CannotQueryOrderException
     */
    public function paginatorOrderItem(Order $order, int $page, int $limit=1): PaginationInterface
    {
        try {
            return $this->orderItemRepository->paginateOrderItem($order, $page, $limit);
        } catch (\Throwable $exception) {
            throw new CannotQueryOrderException($this->translator->trans(CannotQueryOrderException::$messageQuerry));
        }
    }

}
