<?php

namespace App\Service;

use App\Entity\CartItem;
use App\Entity\Product;
use App\Entity\User;
use App\Exception\CartItem\CannotDeleteCartItemException;
use App\Exception\CartItem\CannotQueryCartItemException;
use App\Exception\CartItem\CannotSaveCartItemException;
use App\Repository\CartItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

readonly class CartItemService
{
    public function __construct(
        private EntityManagerInterface $em,
        private CartItemRepository $cartItemRepository,
        private readonly TranslatorInterface $translator
    )
    {
    }

    /**
     * @throws CannotQueryCartItemException
     */
    public function paginateProduct(User $user, $page, $limit = 5): PaginationInterface
    {
        try {
            return $this->cartItemRepository->paginateCartItem($user, $page, $limit);
        } catch (\Throwable $exception) {
            throw new CannotQueryCartItemException($this->translator->trans(CannotQueryCartItemException::$messageQuerry));
        }
    }

    public function createColumn(): array
    {
        $tabColumn = ['picture', 'name', 'unit_price', 'quantity', 'action'];
        return array_map(fn($column) => $this->translator->trans('cart_item.list.column.' . $column), $tabColumn);
    }

    /**
     * @throws CannotQueryCartItemException
     */
    public function findByUserAndProduct(User $user, Product $product): ?CartItem
    {
        try {
            return $this->cartItemRepository->findOneBy(['user' => $user, 'product' => $product]);
        } catch (\Throwable $exception) {
            throw new CannotQueryCartItemException($this->translator->trans(CannotQueryCartItemException::$messageQuerry));
        }
    }

    /**
     * @throws CannotQueryCartItemException
     */
    public function findAllProductsByUser(User $user): array
    {
        try {
            return $this->cartItemRepository->findProductIdsByUser($user);
        } catch (\Throwable $exception) {
            throw new CannotQueryCartItemException($this->translator->trans(CannotQueryCartItemException::$messageQuerry));
        }
    }

    /**
     * @throws CannotQueryCartItemException
     */
    public function findOneByProductAndUser(User $user, Product $product): ?CartItem
    {
        try {
            return $this->cartItemRepository->findOneBy(['product' => $product, 'user' => $user]);
        } catch (\Throwable $exception) {
            throw new CannotQueryCartItemException($this->translator->trans(CannotQueryCartItemException::$messageQuerry));
        }
    }

    /**
     * @throws CannotSaveCartItemException
     */
    public function insertCartItem(User $user, Product $product, CartItem $cartItem): void
    {
        try {
            $cartItem->setUser($user);
            $cartItem->setProduct($product);
            $this->em->persist($cartItem);
            $this->em->flush();
        } catch (\Throwable $exception) {
            throw new CannotSaveCartItemException($this->translator->trans(CannotSaveCartItemException::$messageSave));
        }

    }

    /**
     * @throws CannotDeleteCartItemException
     */
    public function removeCartItem(CartItem $cartItem): void
    {
        try {
            $this->em->remove($cartItem);
            $this->em->flush();
        } catch (\Throwable $exception) {
            throw new CannotDeleteCartItemException($this->translator->trans(CannotDeleteCartItemException::$messageDelete));
        }

    }


    public function setQuantity(int $quantity = 1): CartItem
    {
        $cartItem = new CartItem();
        return $cartItem->setQuantity($quantity);
    }

    /**
     * @throws CannotQueryCartItemException
     */
    public function isProductInCartItem(Product $product): array
    {
        try {
            return $this->cartItemRepository->findBy(['product' => $product]);
        } catch (\Throwable $exception) {
            throw new CannotQueryCartItemException($this->translator->trans(CannotQueryCartItemException::$messageQuerry));
        }

    }


}
