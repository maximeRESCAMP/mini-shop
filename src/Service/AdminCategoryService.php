<?php

namespace App\Service;

use App\Entity\Category;
use App\Exception\Admin\Category\CannotDeleteCategoryException;
use App\Exception\Admin\Category\CannotQueryCategoryException;
use App\Exception\Admin\Category\CannotSaveCategoryException;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

readonly class AdminCategoryService
{
    public function __construct(private CategoryRepository $categoryRepository, private EntityManagerInterface $em)
    {
    }

    public function findAll(): array
    {
        try {
            return $this->categoryRepository->findAll();
        } catch (\Throwable $exception) {
            throw new (CannotQueryCategoryException::$messageQuerry);
        }
    }

    public function paginateCategory(int $page){
        try {
            return $this->categoryRepository->paginateCategory($page);
        } catch (\Throwable $exception) {
            throw new (CannotQueryCategoryException::$messageQuerry);
        }
    }

    public function findOneByCategory(string $slug): Category
    {
        try {
            return $this->categoryRepository->findOneBy(['slug'=>$slug]);
        } catch (\Throwable $exception) {
            throw new (CannotQueryCategoryException::$messageQuerry);
        }
    }

    public function save(Category $category): void
    {
        try {
            $this->em->persist($category);
            $this->em->flush();
        } catch (\Throwable $exception) {
            throw new (CannotSaveCategoryException::$messageSave);
        }

    }

    public function remove(Category $category): void
    {
        try {
            $this->em->remove($category);
            $this->em->flush();
        } catch (\Throwable $exception) {
            throw new (CannotDeleteCategoryException::$messageRemove);
        }

    }

}
