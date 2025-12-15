<?php

namespace App\Tests\Unit\Service;

use App\Repository\ProductRepository;
use App\Service\ProductService;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductServiceTest extends TestCase
{
    public function testCreateColumn(): void
    {
        $addressService = $this->createProductService();
        $tabColumn = ['product.list.column.picture', 'product.list.column.category_name', 'product.list.column.product_name', 'product.list.column.slug', 'product.list.column.description', 'product.list.column.price', 'product.list.column.stock', 'product.list.column.action'];
        $this->assertEquals($tabColumn, $addressService->createColumn(), 'echec product service test test create column  admin');
    }

    public function testCreateColumnNoAdmin(): void
    {
        $addressService = $this->createProductService();
        $tabColumn = ['product.list.column.picture', 'product.list.column.category_name', 'product.list.column.product_name', 'product.list.column.price', 'product.list.column.action'];
        $this->assertEquals($tabColumn, $addressService->createColumnNoAdmin(), 'echec product service test test create column no admin');
    }

    public function createProductService(): ProductService
    {
        $translatorMock = $this->createMock(TranslatorInterface::class);

        $translatorMock->method('trans')
            ->willReturnCallback(fn($key) => $key);
        return new ProductService(
            $this->createMock(ProductRepository::class),
            $translatorMock
        );

    }

}
