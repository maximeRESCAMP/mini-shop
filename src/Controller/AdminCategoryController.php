<?php

namespace App\Controller;

use App\Entity\Category;
use App\Exception\Admin\Category\CannotDeleteCategoryException;
use App\Exception\Admin\Category\CannotQueryCategoryException;
use App\Exception\Admin\Category\CannotSaveCategoryException;
use App\Exception\Product\CannotQueryProductException;
use App\Form\CategoryType;
use App\Service\AdminCategoryService;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin/category', name: 'app_admin_category_')]
final class AdminCategoryController extends AbstractController
{
    public function __construct(private readonly AdminCategoryService $adminCategoryService, private readonly ProductService $productService, private TranslatorInterface $translator)
    {
    }

    /**
     * @throws CannotQueryCategoryException
     */
    #[Route('', name: 'list', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);

        return $this->render('admin_category/index.html.twig', [
            'categories' => $this->adminCategoryService->paginateCategory($page),
            'nom_col' => $this->adminCategoryService->createColumn(),
            'val_filter' => ['c.name', 'c.slug', null],
        ]);
    }

    /**
     * @throws CannotSaveCategoryException
     */
    #[Route('/add', name: 'add')]
    public function create(Request $request): Response
    {
        $form = $this->createForm(CategoryType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $this->adminCategoryService->save($category);
            $this->addFlash(
                'success',
                $this->translator->trans('category.message.add.success')
            );
            return $this->redirectToRoute('app_admin_category_list');
        }
        return $this->render('admin_category/form.html.twig', [
            'form' => $form,
            'is_update' => false,
        ]);
    }

    /**
     * @throws CannotQueryProductException
     * @throws CannotDeleteCategoryException
     */
    #[Route('/remove/{id}', name: 'remove', requirements: ['id' => '\d+'], methods: ['POST', 'GET'])]
    public function remove(Category $category): Response
    {
        if (!is_null($this->productService->findOneByCategory($category))) {
            $this->addFlash(
                'danger',
                $this->translator->trans('product.error.product_associate_category')
            );
        } else {
            $this->adminCategoryService->remove($category);
            $this->addFlash(
                'success',
                $this->translator->trans('category.message.delete.success')
            );
        }
        return $this->redirectToRoute('app_admin_category_list');
    }

    /**
     * @throws CannotQueryCategoryException
     * @throws CannotSaveCategoryException
     */
    #[Route('/update/{slug}', name: 'update', methods: ['GET', 'POST'])]
    public function update(string $slug, Request $request): Response
    {
        $category = $this->adminCategoryService->findOneByCategory($slug);
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $this->adminCategoryService->save($category);
            $this->addFlash(
                'success',
                $this->translator->trans('category.message.update.success')
            );
            return $this->redirectToRoute('app_admin_category_list');
        }
        return $this->render('admin_category/form.html.twig', [
            'form' => $form,
            'is_update' => true,
        ]);
    }


}
