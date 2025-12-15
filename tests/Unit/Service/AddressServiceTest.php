<?php

namespace App\Tests\Unit\Service;

use App\Repository\AddressRepository;
use App\Repository\OrderRepository;
use App\Service\AddressService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class AddressServiceTest extends TestCase
{

    public function testCreateColumn(): void
    {
        $addressService = self::createAddressService();
        $tabColumn = ['delivery.address.list.column.country', 'delivery.address.list.column.zip_code', 'delivery.address.list.column.city', 'delivery.address.list.column.street', 'delivery.address.list.column.action'];
        $this->assertEquals($tabColumn, $addressService->createColumn());
    }

    public function createAddressService(): AddressService
    {
        $translatorMock = $this->createMock(TranslatorInterface::class);

        $translatorMock->method('trans')
            ->willReturnCallback(fn($key) => $key);
        return new AddressService(
            $this->createMock(EntityManagerInterface::class),
            $this->createMock(AddressRepository::class),
            $this->createMock(OrderRepository::class),
            $translatorMock
        );

    }

}
