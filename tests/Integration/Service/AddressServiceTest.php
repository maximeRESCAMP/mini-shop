<?php

namespace App\Tests\Integration\Service;

use App\Entity\Address;
use App\Entity\User;
use App\Repository\AddressRepository;
use App\Repository\OrderRepository;
use App\Service\AddressService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class AddressServiceTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testSave(): void
    {
        $address= new Address();
        $user = new User();
        $user->setFirstName('John');
        $user->setLastName('Doe');
        $user->setEmail('john.doe@hotmail.fr');
        $user->setPhone('0123456789');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword();
        $address->setUser($user);
        $address->setCountry('FR');
        $address->setZipCode('12345');
        $address->setCity('Paris');
        $address->setStreet('rue des banane');
        $addressService = $this->createAddressService();
        $addressService->save($address);
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
