<?php

namespace App\Service;

use App\Entity\Category;
use App\Exception\Admin\Category\CannotDeleteCategoryException;
use App\Exception\Admin\Category\CannotQueryCategoryException;
use App\Exception\Admin\Category\CannotSaveCategoryException;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

readonly class AdminCategoryService
{
    public function __construct(private CategoryRepository $categoryRepository, private EntityManagerInterface $em, private TranslatorInterface $translator)
    {
    }

    /**
     * @throws CannotQueryCategoryException
     */
    public function paginateCategory(int $page):PaginationInterface{
        try {
            return $this->categoryRepository->paginateCategory($page);
        } catch (\Throwable $exception) {
            throw new CannotQueryCategoryException($this->translator->trans(CannotQueryCategoryException::$messageQuerry));
        }
    }

    public function createColumn():array{
        $tabColumn = ['name','slug','action'];
        return array_map(fn($column)=>$this->translator->trans('category.list.column.'.$column),$tabColumn);
    }


    /**
     * @throws CannotSaveCategoryException
     */
    public function save(Category $category): void
    {
        try {
            $this->em->persist($category);
            $this->em->flush();
        } catch (\Throwable $exception) {
            throw new CannotSaveCategoryException($this->translator->trans(CannotSaveCategoryException::$messageSave));
        }
    }

    /**
     * @throws CannotQueryCategoryException
     */
    public function findOneByCategory(string $slug): Category
    {
        try {
            return $this->categoryRepository->findOneBy(['slug'=>$slug]);
        } catch (\Throwable $exception) {
            throw new CannotQueryCategoryException($this->translator->trans(CannotQueryCategoryException::$messageQuerry));
        }
    }

    /**
     * @throws CannotDeleteCategoryException
     */
    public function remove(Category $category): void
    {
        try {
            $this->em->remove($category);
            $this->em->flush();
        } catch (\Throwable $exception) {
            throw new CannotDeleteCategoryException($this->translator->trans(CannotDeleteCategoryException::$messageRemove));
        }

    }

}
