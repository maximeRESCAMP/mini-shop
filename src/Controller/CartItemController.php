<?php

namespace App\Controller;

use App\Entity\CartItem;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/cart/item/', name: 'app_cart_item_')]
final class CartItemController extends AbstractController
{
    #[Route('add/{id}', name: 'add', requirements: ['id' => '\d+'])]
    public function add(Product $product, #[CurrentUser] ?User $user, EntityManagerInterface $em): Response
    {

        if ($product->getStock() > 0) {
            $cartItem = new CartItem();
            $cartItem->setUser($user);
            $cartItem->setProduct($product);
            $cartItem->setQuantity(1);
            $em->persist($cartItem);
            $em->flush();
            $this->addFlash(
                'success',
                'L\'article a été ajouté au panier !'
            );
            return $this->redirectToRoute('app_product_');

        } else {
            $this->addFlash(
                'danger',
                'Plus de stock pour cette article !'
            );
        }
        return $this->redirectToRoute('app_product_');

    }

    #[Route('remove/{id}', name: 'remove', requirements: ['id' => '\d+'])]
    public function remove(Product $product, EntityManagerInterface $em, #[CurrentUser] ?User $user): Response
    {
        $cartItem = $em->getRepository(CartItem::class)->findOneBy(['product' => $product, 'user' => $user]);
        $user->removeCartItem($cartItem);
        $em->persist($user);
        $em->flush();
        $this->addFlash(
            'success',
            'L\'article a été supprimé du panier !'
        );


        return $this->redirectToRoute('app_product_');


    }

    #[Route('', name: '')]
    public function list(EntityManagerInterface $em, #[CurrentUser] ?User $user): Response
    {
        $tabCartItem = $em->getRepository(CartItem::class)->findBy(['user' => $user]);
        return $this->render('cart_item/index.html.twig', [
            'tabCartItem' => $tabCartItem,
        ]);
    }

    #[Route('removeCart/{id}', name: 'removeCart', requirements: ['id' => '\d+'])]

    public function removeByCartItem(CartItem $cartItem, EntityManagerInterface $em): Response
    {
        $em->remove($cartItem);
        $em->flush();
        $this->addFlash(
            'success',
            'L\'article a été supprimé du panier !'
        );
        return $this->redirectToRoute('app_cart_item_');

    }

}
