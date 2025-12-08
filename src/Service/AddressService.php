<?php

namespace App\Service;

use App\Entity\Address;
use App\Entity\User;
use App\Exception\Address\AddressAlreadyExistsException;
use App\Exception\Address\CannotQueryAddressException;
use App\Exception\Address\CannotSaveAddressException;
use App\Repository\AddressRepository;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

readonly class AddressService
{
    public function __construct(
        private EntityManagerInterface $em,
        private AddressRepository $addressRepository,
        private OrderRepository $orderRepository,
        private TranslatorInterface $translatorInterface
    )
    {
    }

    /**
     * @throws CannotQueryAddressException
     */
    public function paginateAddressByUser(User $user, int $page = 1, int $limit = 10): PaginationInterface
    {
        try {
            return $this->addressRepository->paginateAddressByUser($user, $page, $limit);
        } catch (\Throwable $th) {
            throw new CannotQueryAddressException($this->translatorInterface->trans(CannotQueryAddressException::$queryMessage));
        }
    }

    public function createColumn():array{
        $tabColumn =['country', 'zip_code', 'city', 'street', 'action'];
       return array_map(fn($column)=>$this->translatorInterface->trans('delivery.address.list.column.'.$column),$tabColumn);
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
            throw new CannotQueryAddressException($this->translatorInterface->trans(CannotQueryAddressException::$queryMessage));
        }
        if ($exists && $exists->getId() !== $address->getId()) {
            throw new AddressAlreadyExistsException($this->translatorInterface->trans(AddressAlreadyExistsException::$addressAlready));
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
            throw new CannotSaveAddressException($this->translatorInterface->trans(CannotSaveAddressException::$cannotMessage));
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
