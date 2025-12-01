<?php

namespace App\Controller;

use App\Entity\Category;
use App\Exception\Product\CannotFoundProductException;
use App\Form\CategoryType;
use App\Service\AdminCategoryService;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/category', name: 'app_admin_category_')]
final class AdminCategoryController extends AbstractController
{
    public function __construct(private readonly AdminCategoryService $adminCategoryService, private readonly ProductService $productService)
    {
    }

    #[Route('', name: 'list', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin_category/index.html.twig', [
            'categories' => $this->adminCategoryService->findAll(),
            'nom_col' => ['Nom','Slug','Action'],

        ]);
    }

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
                'Ajout de la catégorie réussi!'
            );
            return $this->redirectToRoute('app_admin_category_list');
        }
        return $this->render('admin_category/form.html.twig', [
            'form' => $form,
            'is_update' => false,
        ]);
    }

    /**
     * @throws CannotFoundProductException
     */
    #[Route('/remove/{id}', name: 'remove', requirements: ['id' => '\d+'], methods: ['POST', 'GET'])]
    public function remove(Category $category): Response
    {
        if (!is_null($this->productService->findOneByCategory($category))) {
            $this->addFlash(
                'danger',
                'Des produits sont associer à cette catégorie veuiller supprimer les produits d\'abbord!'
            );
        } else {
            $this->adminCategoryService->remove($category);
            $this->addFlash(
                'success',
                'Catégorie supprimmer!'
            );
        }
        return $this->redirectToRoute('app_admin_category_list');
    }

    #[Route('/update/{id}', name: 'update', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function update(Category $category, Request $request): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            try {
                $this->adminCategoryService->save($category);
                $this->addFlash(
                    'success',
                    'Modification réussi!'
                );
            } catch (\Throwable $exception) {
                $this->addFlash(
                    'danger',
                    'Erreur lors de la modification de la categorie!'
                );
            }

            return $this->redirectToRoute('app_admin_category_list');
        }
        return $this->render('admin_category/form.html.twig', [
            'form' => $form,
            'is_update' => true,
        ]);
    }


}
