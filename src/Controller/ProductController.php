<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Exception\CartItem\CannotQueryCartItemException;
use App\Exception\CartItem\CannotSaveCartItemException;
use App\Exception\Product\CannotFoundProductException;
use App\Exception\Product\CannotQueryProductException;
use App\Form\CartItemType;
use App\Service\CartItemService;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/product', name: 'app_product_')]
final class ProductController extends AbstractController
{
    public function __construct(private readonly ProductService $productService, private readonly CartItemService $cartItemService, private readonly TranslatorInterface $translator)
    {
    }


    /**
     * @throws CannotQueryCartItemException
     * @throws CannotQueryProductException
     */
    #[Route('', name: 'list')]
    public function index(#[CurrentUser] ?User $user, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);

        return $this->render('product/index.html.twig', [
            'products' => $this->productService->paginateProduct($page),
            'itemsCartOrder' => $this->cartItemService->findAllProductsByUser($user),
            'nom_col' => $this->productService->createColumnNoAdmin(),
            'val_filter' => [null, 'c.name', 'p.name', 'p.price', null],
        ]);
    }

    /**
     * @throws CannotQueryCartItemException
     * @throws CannotSaveCartItemException
     * @throws CannotQueryProductException
     */
    #[Route('/detail/{slug}', name: 'detail')]
    public function detail(string $slug, Request $request, #[CurrentUser] ?User $user): Response
    {
        $product = $this->productService->findBySLug($slug);
        $form = null;
        $rupture = $this->productService->checkIfRupture($product);
        $inCartItem = $this->cartItemService->findOneByProductAndUser($user, $product);
        if (!$rupture && is_null($inCartItem)) {
            $form = $this->createForm(CartItemType::class);
            $form->handleRequest($request);
        }

        if (isset($form) && $form->isSubmitted()) {
            $cartItem = $form->getData();

            if (!$this->productService->checkIfStockSupOrder($product, $cartItem)) {
                $form->get('quantity')->addError(new FormError($this->translator->trans('product.form.error.not_all_product')));
            }

            if ($form->isValid()) {
                $this->cartItemService->insertCartItem($user, $product, $cartItem);
                $this->addFlash(
                    'success',
                    $this->translator->trans('product.message.add_cart_item')
                );
                return $this->redirectToRoute('app_product_list');

            }
        }

        return $this->render('product/detail.html.twig', [
            'product' => $product,
            'form' => $form,
            'rupture' => $rupture,
            'inCartItem' => $inCartItem,
        ]);
    }
}
