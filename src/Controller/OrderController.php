<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use App\Exception\Order\CannotQueryOrderException;
use App\Security\CartItemVoter;
use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/order', name: 'app_order_')]
final class OrderController extends AbstractController
{
    public function __construct(private readonly OrderService $orderService)
    {
    }

    /**
     * @throws CannotQueryOrderException
     */
    #[Route('', name: 'list')]
    public function index(#[CurrentUser] ?User $user): Response
    {
        return $this->render('order/index.html.twig', [
            'orders' => $this->orderService->findOrderByUser($user),
        ]);
    }

    /**
     * @throws CannotQueryOrderException
     */
    #[Route('/detail/{id}', name: 'detail_order', requirements: ['id' => '\d+'])]
    public function detail(Order $order): Response
    {
        $this->denyAccessUnlessGranted(CartItemVoter::DELETE, $order);
        return $this->render('order/detail.html.twig', [
            'order' => $this->orderService->findOrder($order),
        ]);
    }
}
