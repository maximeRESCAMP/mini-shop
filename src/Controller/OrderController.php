<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use App\Exception\Order\CannotQueryOrderException;
use App\Security\CartItemVoter;
use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(#[CurrentUser] ?User $user, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        return $this->render('order/index.html.twig', [
            'orders' => $this->orderService->paginateOrder($user, $page),
            'nom_col' => $this->orderService->createColumn(),
            'val_filter' => ['o.createdAt', 'o.reference', 'a.city', 'o.total','o.status'],
        ]);
    }

    /**
     * @throws CannotQueryOrderException
     */
    #[Route('/detail/{id}', name: 'detail_order', requirements: ['id' => '\d+'])]
    public function detail(Order $order,Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $this->denyAccessUnlessGranted(CartItemVoter::DELETE, $order);
        return $this->render('order/detail.html.twig', [
            'ordersItems' => $this->orderService->paginatorOrderItem($order,$page),
        ]);
    }
}

