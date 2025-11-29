<?php

namespace App\Service;

use App\Entity\Product;
use App\Exception\Admin\Product\CannotDeleteProductException;
use App\Exception\Admin\Product\CannotSaveProductException;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class AdminProductService
{

    public function __construct(private readonly EntityManagerInterface $em, private ProductRepository $productRepository)
    {
    }

    /**
     * @throws CannotSaveProductException
     */
    public function save(Product $product): void
    {
        try {
            $this->em->persist($product);
            $this->em->flush();
        } catch (\Throwable $exception) {
            throw new CannotSaveProductException(CannotSaveProductException::$messageQuerry);
        }
    }

    /**
     * @throws CannotDeleteProductException
     */
    public function remove(Product $product): void
    {
        try {
            $this->em->remove($product);
            $this->em->flush();
        } catch (\Throwable $exception) {
            throw new CannotDeleteProductException(CannotDeleteProductException::$messageDelete);
        }
    }

}
