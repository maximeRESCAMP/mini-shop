<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\CartItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CartItemRepository::class)]
#[ORM\Table(
    name: 'cart_item',
    uniqueConstraints: [
        new ORM\UniqueConstraint(name: 'user_product_unique', columns: ['user_id', 'product_id'])
    ]
)]
#[UniqueEntity(
    fields: ['user', 'product'],
    message: 'Ce produit est déjà dans votre panier'
)]
#[ORM\HasLifecycleCallbacks]
class CartItem
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'cartItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'cartItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\Column]
    #[Assert\Positive(message: 'La valeur doit être supérieur a 0')]
    #[Assert\Type(type: 'integer', message: 'La quantité doit être un nombre entier')]
    #[Assert\LessThanOrEqual(
        value: 10000,
        message: 'La quantité ne peut pas dépasser {{ compared_value }}'
    )]
    private ?int $quantity = 1;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }
}
