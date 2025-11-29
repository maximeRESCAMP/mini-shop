<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\User;
use App\Exception\Order\CannotQueryOrderException;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;

readonly class OrderService
{
    public function __construct(private OrderRepository $orderRepository)
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
            throw new CannotQueryOrderException($exception);
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
            throw new CannotQueryOrderException($exception);
        }
    }

}
