<?php

namespace App\Service;

use App\Entity\Address;
use App\Entity\User;
use App\Exception\AddressAlreadyExistsException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use function Webmozart\Assert\Tests\StaticAnalysis\throws;

readonly class AddressService
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function findByUser(User $user): array
    {
        return $this->em->getRepository(Address::class)->findBy(['user' => $user]);
    }

    /**
     * @throws AddressAlreadyExistsException
     */
    public function ensureUniqueForUser(Address $address, User $user): void
    {
        $exists  = $this->em->getRepository(Address::class)->findOneBy([
            'zipCode' => $address->getZipCode(),
            'street' => $address->getStreet(),
            'city' => $address->getCity(),
            'country' => $address->getCountry(),
            'user' => $user,
        ]);
        if ($exists && $exists->getId() !== $address->getId()) {
            throw new AddressAlreadyExistsException(AddressAlreadyExistsException::$addressAlready);
        }
    }

    public function save(Address $address): void
    {
        $this->em->persist($address);
        $this->em->flush();
    }

    public function dissociateAddressFromUser(User $user, Address $address): Address
    {
        return $address->setUser(null);
    }


    public function assignUser(Address $address, User $user): Address
    {
        return $address->setUser($user);
    }

    public function userOwnsAddress(Address $address, User $user): bool
    {
        return $user->getAddresses()->contains($address);
    }

}
