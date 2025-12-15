<?php

namespace App\Tests\Unit\Service;

use App\Repository\OrderItemRepository;
use App\Repository\OrderRepository;
use App\Service\OrderService;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class OrderServiceTest extends TestCase
{

    public function testCreateColumn(){
        $tabColumn =["order.list.column.created_at","order.list.column.reference","order.list.column.address","order.list.column.total","order.list.column.status"];
        $this->assertEquals($tabColumn,$this->createOrderService()->createColumn());
    }

    public function createOrderService(): OrderService
    {
        $translator = $this->createMock(TranslatorInterface::class);
        $translator->method('trans')->willReturnCallback(fn($key) => $key);
        return new OrderService(
            $this->createMock(OrderRepository::class),
            $this->createMock(OrderItemRepository::class),
            $translator
        );
    }

}
