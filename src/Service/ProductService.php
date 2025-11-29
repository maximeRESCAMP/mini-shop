<?php

namespace App\Service;

use App\Entity\CartItem;
use App\Entity\Category;
use App\Entity\Product;
use App\Exception\Product\CannotFoundProductException;
use App\Repository\ProductRepository;

readonly class ProductService
{
    public function __construct(private ProductRepository $productRepository)
    {
    }

    /**
     * @throws CannotFoundProductException
     */
    public function findAll(): array
    {
        try {
            return $this->productRepository->findAll();
        } catch (\Throwable $exception) {
            throw new CannotFoundProductException(CannotFoundProductException::$messageNotFound);
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
     * @throws CannotFoundProductException
     */
    public function findOneByCategory(Category $category): Product|null
    {
        try {
            return $this->productRepository->findOneBy(['category' => $category]);

        } catch (\Throwable $exception) {
            throw new CannotFoundProductException(CannotFoundProductException::$messageNotFound);
        }
    }


}
