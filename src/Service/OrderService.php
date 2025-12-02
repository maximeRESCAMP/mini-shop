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

readonly class OrderService
{
    public function __construct(private OrderRepository $orderRepository, private OrderItemRepository $orderItemRepository)
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
            throw new CannotQueryOrderException(CannotQueryOrderException::$messageQuerry);
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
            throw new CannotQueryOrderException(CannotQueryOrderException::$messageQuerry);
        }
    }

    /**
     * @throws CannotQueryOrderException
     */
    public function findOrder(Order $order): Order
    {
        try {
            return $this->orderRepository->findOneBy(['id' => $order->getId()]);

        } catch (\Throwable $exception) {
            throw new CannotQueryOrderException(CannotQueryOrderException::$messageQuerry);
        }
    }

    /**
     * @throws CannotQueryOrderException
     */
    public function paginatorOrderItem(Order $order, int $page, int $limit=1): PaginationInterface
    {
        try {
            return $this->orderItemRepository->paginateOrderItem($order, $page, $limit);

        } catch (\Throwable $exception) {
            dd($exception->getMessage());
            throw new CannotQueryOrderException(CannotQueryOrderException::$messageQuerry);
        }
    }

}
