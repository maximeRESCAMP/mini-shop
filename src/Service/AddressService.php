<?php

namespace App\Service;

use App\Entity\Address;
use App\Entity\User;
use App\Exception\Address\AddressAlreadyExistsException;
use App\Exception\Address\AddressUserNotFoundException;
use App\Exception\Address\CannotQueryAddressException;
use App\Exception\Address\CannotSaveAddressException;
use App\Repository\AddressRepository;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

readonly class AddressService
{
    public function __construct(private EntityManagerInterface $em, private AddressRepository $addressRepository, private OrderRepository $orderRepository)
    {
    }

    /**
     * @throws AddressUserNotFoundException
     */
    public function findByUser(?User $user): array
    {
        try {
            return $this->addressRepository->findBy(['user' => $user]);
        } catch (\Throwable $th) {
            throw new AddressUserNotFoundException(AddressUserNotFoundException::$userNotFound);
        }
    }

    public function paginateAddressByUser(User $user, int $page = 1, int $limit = 10): PaginationInterface
    {
        return $this->addressRepository->paginateAddressByUser($user, $page, $limit);
    }

    /**
     * @throws AddressAlreadyExistsException|CannotQueryAddressException
     */
    public function uniqueForUser(Address $address, User $user): bool
    {
        try {
            $exists = $this->addressRepository->findOneBy([
                'zipCode' => $address->getZipCode(),
                'street' => $address->getStreet(),
                'city' => $address->getCity(),
                'country' => $address->getCountry(),
                'user' => $user,
            ]);
        } catch (\Throwable $th) {
            throw new CannotQueryAddressException(CannotQueryAddressException::$cannotMessage);
        }
        if ($exists && $exists->getId() !== $address->getId()) {
            throw new AddressAlreadyExistsException(AddressAlreadyExistsException::$addressAlready);
        }
        return true;
    }

    /**
     * @throws CannotSaveAddressException
     */
    public function save(Address $address): void
    {
        try {
            $this->em->persist($address);
            $this->em->flush();
        } catch (\Throwable $th) {
            throw new CannotSaveAddressException(CannotSaveAddressException::$cannotMessage);
        }

    }

    /**
     * @throws CannotSaveAddressException
     */
    public function dissociateAddressFromUser(Address $address, User $user): void
    {
        if (empty($this->orderRepository->findBy(['address' => $address]))) {
            $user->removeAddress($address);
            $this->em->remove($address);
            $this->em->flush();
        } else {
            $address->setUser(null);
            $this->save($address);

        }


    }

    /**
     * @throws CannotSaveAddressException
     */
    public function assignUser(Address $address, User $user): void
    {
        $address->setUser($user);
        $this->save($address);
    }

}
