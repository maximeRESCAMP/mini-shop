<?php

namespace App\Controller;

use App\Entity\Product;
use App\Exception\Admin\Product\CannotDeleteProductException;
use App\Exception\Admin\Product\CannotSaveProductException;
use App\Exception\CartItem\CannotQueryCartItemException;
use App\Exception\NotGoodExtension;
use App\Exception\Product\CannotQueryProductException;
use App\Form\ProductType;
use App\Service\AdminProductService;
use App\Service\CartItemService;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin/product', name: 'app_admin_product_')]
class AdminProductController extends AbstractController
{
    public function __construct(
        private readonly AdminProductService $adminProductService,
        private readonly ProductService      $productService,
        private readonly CartItemService     $cartItemService,
        private readonly TranslatorInterface $translator
    )
    {
    }


    /**
     * @throws CannotQueryProductException
     */
    #[Route('', name: 'list', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        return $this->render('admin_product/index.html.twig', [
            'products' => $this->productService->paginateProduct($page),
            'nom_col' => $this->productService->createColumn(),
            'val_filter' => [null, 'c.name', 'p.name', 'p.slug', 'p.description', 'p.price', 'p.stock', null],

        ]);
    }


    /**
     * @throws CannotSaveProductException
     * @throws NotGoodExtension
     */
    #[Route('/add', name: 'add', methods: ['GET', 'POST'])]
    public function add(Request $request): Response
    {
        $form = $this->createForm(ProductType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $file = $form['picture']->getData();
            if (isset($file)) {
                $this->adminProductService->downloadPicture($product, $file);
            }

            $this->adminProductService->save($product);
            $this->addFlash(
                'success',
                $this->translator->trans('product.message.add.success')
            );
            return $this->redirectToRoute('app_admin_product_list');
        }

        return $this->render('admin_product/form.html.twig', [
            'form' => $form,
            'is_update' => false,
        ]);
    }

    /**
     * @throws CannotDeleteProductException
     * @throws CannotQueryCartItemException
     */
    #[Route('/remove/{id}', name: 'remove', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function remove(Product $product): Response
    {
        if ($this->cartItemService->isProductInCartItem($product)) {
            $this->addFlash(
                'danger',
                $this->translator->trans('cart_item.message.delete.danger')
            );
        } else {
            $this->adminProductService->remove($product);
            $this->addFlash(
                'success',
                $this->translator->trans('product.message.remove.success')
            );
        }


        return $this->redirectToRoute('app_admin_product_list');
    }

    /**
     * @throws CannotSaveProductException
     * @throws NotGoodExtension
     */
    #[Route('update/{id}', name: 'update', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function update(Product $product, Request $request): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $file = $form['picture']->getData();
            if (isset($file)) {
                $this->adminProductService->downloadPicture($product, $file);
            }
            $this->adminProductService->save($product);
            $this->addFlash(
                'success',
                $this->translator->trans('product.message.update.success')

            );
            return $this->redirectToRoute('app_admin_product_list');
        }
        return $this->render('admin_product/form.html.twig', [
            'form' => $form,
            'is_update' => true,
        ]);
    }
}
