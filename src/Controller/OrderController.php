<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/order/', name: 'app_order_')]

final class OrderController extends AbstractController
{
    #[Route('', name: '')]
    public function index(#[CurrentUser] ?User $user, EntityManagerInterface $em): Response
    {
        $tabOrders = $em->getRepository(Order::class)->findBy(['user' => $user]);
        return $this->render('order/detail.html.twig', [
            'tabOrders' => $tabOrders,
        ]);
    }
}
