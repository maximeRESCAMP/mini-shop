<?php

namespace App\Controller;

use App\Entity\CartItem;
use App\Entity\Product;
use App\Entity\User;
use App\Exception\CartItem\CannotDeleteCartItemException;
use App\Exception\CartItem\CannotQueryCartItemException;
use App\Exception\CartItem\CannotSaveCartItemException;
use App\Security\CartItemVoter;
use App\Service\CartItemService;
use App\Service\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/cart/item', name: 'app_cart_item_')]
final class CartItemController extends AbstractController
{
    public function __construct(private readonly CartItemService $cartItemService, private readonly ProductService $productService)
    {
    }

    /**
     * @throws CannotQueryCartItemException
     */
    #[Route('', name: 'list')]
    public function index(#[CurrentUser] ?User $user): Response
    {
        return $this->render('cart_item/index.html.twig', [
            'cartItems' => $this->cartItemService->findByUser($user),
        ]);
    }

    /**
     * @throws CannotSaveCartItemException
     */
    #[Route('/add/{id}', name: 'add', requirements: ['id' => '\d+'])]
    public function add(Product $product, #[CurrentUser] ?User $user): Response
    {
        $isRupture = $this->productService->checkIfRupture($product);
        if ($isRupture) {
            $this->addFlash(
                'danger',
                'Plus de stock pour cette article !'
            );
        } else {
            $this->cartItemService->insertCartItem($user, $product,$this->cartItemService->setQuantity());
            $this->addFlash(
                'success',
                'L\'article a été ajouté au panier !'
            );
        }
        return $this->redirectToRoute('app_product_list');

    }


    /**
     * @throws CannotQueryCartItemException
     * @throws CannotDeleteCartItemException
     */
    #[Route('/deleteProduct/{id}', name: 'delete_by_product', requirements: ['id' => '\d+'])]
    public function deleteProduct(Product $product, #[CurrentUser] ?User $user, CartItemService $cartItemService): Response
    {
        $cartItem = $cartItemService->findByUserAndProduct($user, $product);
        $cartItemService->removeCartItem($cartItem);
        $this->addFlash(
            'success',
            'L\'article a été supprimé du panier !'
        );
        return $this->redirectToRoute('app_product_list');
    }

    /**
     * @throws CannotDeleteCartItemException
     */
    #[Route('/deleteCart/{id}', name: 'delete_cart', requirements: ['id' => '\d+'])]
    public function deleteCart(CartItem $cartItem, #[CurrentUser] ?User $user, CartItemService $cartItemService): Response
    {
        $this->denyAccessUnlessGranted(CartItemVoter::DELETE, $cartItem);
        $cartItemService->removeCartItem($cartItem);
        $this->addFlash(
            'success',
            'L\'article a été supprimé du panier !'
        );
        return $this->redirectToRoute('app_product_list');
    }
}
