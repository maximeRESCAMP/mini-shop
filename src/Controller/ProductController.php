<?php

namespace App\Controller;

use App\Entity\CartItem;
use App\Entity\Product;
use App\Entity\User;
use App\Form\AddCartItemType;
use App\Form\ProductType;
use App\Repository\CartItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/product/', name: 'app_product_')]
final class ProductController extends AbstractController
{
    #[Route('', name: '')]
    public function list(EntityManagerInterface $em, #[CurrentUser] ?User $user, CartItemRepository $cartItemRepository): Response
    {
        $tabProduct = $em->getRepository(Product::class)->findAll();
        $tabItemCartOrder = $cartItemRepository->findIdByUser($user);
        return $this->render('product/index.html.twig', [
            'products' => $tabProduct,
            'tabItemCartOrder' => $tabItemCartOrder,
        ]);
    }

    #[Route('detail/{id}', name: 'detail', requirements: ['id' => '\d+'])]
    public function detail(EntityManagerInterface $em, Product $product, Request $request, #[CurrentUser] ?User $user): Response
    {
        $form = null;
        $notInStock = false;
        $inCartItem = false;

        if ($product->getStock() < 1) {
            $notInStock = true;
        } elseif ($em->getRepository(CartItem::class)->findOneBy(['product' => $product, 'user' => $user])) {
            $inCartItem = true;
        } else {
            $form = $this->createForm(AddCartItemType::class);
            $form->handleRequest($request);
        }

        if (isset($form) &&$form->isSubmitted()) {
            $productForm = $form->getData();
            if ($product->getStock() < $productForm->getQuantity()) {
                $form->get('quantity')->addError(new FormError('Il n\'y plus que ' . $product->getStock() . ' produit(s) disponible(s).'));
            }
            if ($form->isValid()) {
                $cartItem = new CartItem();
                $cartItem->setUser($user);
                $cartItem->setProduct($product);
                $cartItem->setQuantity($productForm->getQuantity());
                $em->persist($cartItem);
                $em->flush();
                $this->addFlash(
                    'success',
                    'L\'article a été ajouté au panier !'
                );
                return $this->redirectToRoute('app_product_');

            }


        }

        return $this->render('product/detail.html.twig', [
            'product' => $product,
            'form' => $form,
            'notInStock' => $notInStock,
            'inCartItem' => $inCartItem,
        ]);
    }
}
