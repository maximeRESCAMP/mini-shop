<?php

namespace App\Service;

use App\Entity\CartItem;
use App\Entity\Category;
use App\Entity\Product;
use App\Exception\Product\CannotQueryProductException;
use App\Repository\ProductRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

readonly class ProductService
{
    public function __construct(
        private ProductRepository $productRepository,
        private TranslatorInterface $translator)
    {
    }

    /**
     * @throws CannotQueryProductException
     */
    public function paginateProduct($page, $limit=10): PaginationInterface
    {
        try {
            return $this->productRepository->paginateProduct($page,$limit);
        } catch (\Throwable $exception) {
            throw new CannotQueryProductException($this->translator->trans(CannotQueryProductException::$queryMessage));
        }
    }
    public function createColumn():array{
        $tabColumn =['picture', 'category_name', 'product_name', 'slug','description','price','stock', 'action'];
        return array_map(fn($column)=>$this->translator->trans('product.list.column.'.$column),$tabColumn);
    }

    public function createColumnNoAdmin():array{
        $tabColumn= ['picture', 'category_name', 'product_name', 'price', 'action'];
        return array_map(fn($column)=>$this->translator->trans('product.list.column.'.$column),$tabColumn);
    }


    /**
     * @throws CannotQueryProductException
     */
    public function findBySLug(string $slug): Product
    {
        try {
            return $this->productRepository->findOneBy(['slug' => $slug]);
        } catch (\Throwable $exception) {
            throw new CannotQueryProductException(CannotQueryProductException::$queryMessage);
        }
    }

    public function checkIfRupture(Product $product): bool
    {
        return $product->getStock() < 1;
    }


    public function checkIfStockSupOrder(Product $product, CartItem $cartItem): bool
    {
        return ($product->getStock() >= $cartItem->getQuantity());
    }

    /**
     * @throws CannotQueryProductException
     */
    public function findOneByCategory(Category $category): Product|null
    {
        try {
            return $this->productRepository->findOneBy(['category' => $category]);

        } catch (\Throwable $exception) {
            throw new CannotQueryProductException($this->translator->trans(CannotQueryProductException::$queryMessage));
        }
    }


}
