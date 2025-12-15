<?php

namespace App\Tests\Unit\Service;

use App\Repository\CartItemRepository;
use App\Service\CartItemService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class CartItemServiceTest extends TestCase
{

    public function testCreateColumn(): void
    {
        $cartItemService = $this->createCartItemService();
        $this->assertEquals(['cart_item.list.column.picture','cart_item.list.column.name','cart_item.list.column.unit_price','cart_item.list.column.quantity','cart_item.list.column.action'], $cartItemService->createColumn(),'problème test cartItemService createColumn()');
    }
    public function createCartItemService(): CartItemService
    {
        $translator = $this->createMock(TranslatorInterface::class);
        $translator->method('trans')->willReturnCallback(fn($key) => $key);

        return new CartItemService(
            $this->createMock(EntityManagerInterface::class),
            $this->createMock(CartItemRepository::class),
            $translator
        );
    }

}
