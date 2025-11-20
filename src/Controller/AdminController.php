<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\CategoryType;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/', name: 'app_')]
final class AdminController extends AbstractController
{
    #[Route('list-category', name: 'list_category')]
    public function listCategory(EntityManagerInterface $em): Response
    {
        $tabCategory = $em->getRepository(Category::class)->findAll();
        return $this->render('admin/list-category.html.twig', [
            'tabCategory' => $tabCategory
        ]);
    }

    #[Route('add-caterory', name: 'add_category')]
    public function createCategory(EntityManagerInterface $em, Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $em->persist($category);
            $em->flush();
            $this->addFlash(
                'success',
                'Ajout réussi!'
            );
            return $this->redirectToRoute('app_list_category');
        }
        return $this->render('admin/add-category.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('remove-category/{id}', name: 'remove_category', requirements: ['id' => '\d+'])]
    public function removeCategory(Category $category, EntityManagerInterface $em): Response
    {
        $tabProducts = $em->getRepository(Product::class)->findBy(['category' => $category]);
        if (!empty($tabProducts)) {
            $this->addFlash(
                'danger',
                'Des produits sont associer à cette catégorie veuiller supprimer les produits d\'abbord!'
            );
        } else {
            $em->remove($category);
            $em->flush();
            $this->addFlash(
                'success',
                'Catégorie supprimmer!'
            );
        }
        return $this->redirectToRoute('app_list_category');
    }

    #[Route('update-category/{id}', name: 'update_category', requirements: ['id' => '\d+'])]
    public function updateCategory(Category $category, EntityManagerInterface $em, Request $request): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $em->persist($category);
            $em->flush();
            $this->addFlash(
                'success',
                'Modification réussi!'
            );
            return $this->redirectToRoute('app_list_category');
        }
        return $this->render('admin/add-category.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('list-product', name: 'list_product')]
    public function listProduct(EntityManagerInterface $em): Response
    {
        $tabProduct = $em->getRepository(Product::class)->findAll();
        return $this->render('admin/list-product.html.twig', [
            'tabProduct' => $tabProduct
        ]);
    }

    #[Route('add-product', name: 'add_product')]
    public function addProduct(EntityManagerInterface $em, Request $request): Response
    {
        $form = $this->createForm(ProductType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $em->persist($product);
            $em->flush();
            $this->addFlash(
                'success',
                'Ajout du produits réussi!'
            );
            return $this->redirectToRoute('app_list_product');
        }

        return $this->render('admin/add-product.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('remove-product/{id}', name: 'remove_product', requirements: ['id' => '\d+'])]
    public function removeProduct(Product $product, EntityManagerInterface $em): Response
    {
            $em->remove($product);
            $em->flush();
            $this->addFlash(
                'success',
                'Produit supprimmer!'
            );
        return $this->redirectToRoute('app_list_product');
    }

    #[Route('update-product/{id}', name: 'update_product', requirements: ['id' => '\d+'])]
    public function updateProduct(Product $product, EntityManagerInterface $em, Request $request): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $em->persist($product);
            $em->flush();
            $this->addFlash(
                'success',
                'Modification réussi!'
            );
            return $this->redirectToRoute('app_list_product');
        }
        return $this->render('admin/add-product.html.twig', [
            'form' => $form,
        ]);
    }


}
