<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/order/items', name: 'app_order_items_')]
final class OrderItemsController extends AbstractController
{
    #[Route('detail/{id}', name: 'detail_order', requirements: ['id' => '\d+'])]
    public function detail(Order $order, EntityManagerInterface $em): Response
    {
       $order= $em->getRepository(Order::class)->findOneBy(['id' => $order->getId()]);
//       dd($order);
        return $this->render('order_items/detail.html.twig', [
            'order' => $order,
            'orderRef' => $order->getReference(),
        ]);
    }
}
