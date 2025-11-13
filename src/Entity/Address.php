<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
#[UniqueEntity(
    fields: ['zipCode', 'city', 'street', 'country'],
    message: 'Adresse déja existante'
)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 5)]
    #[Assert\NotBlank(message: 'Ce champ ne peut pa être vide')]
    #[Assert\Regex(
        pattern: '/\d{5}/',
        message: 'Le champ  ne doit contenir que des chiffres'
    )]
    private ?string $zipCode = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'Ce champ ne peut pa être vide')]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'La ville doit contenir au moins {{ limit }} caractères',
        maxMessage: 'La ville ne peut pas dépasser {{ limit }} caractères'
    )]
    #[Assert\Regex(
        pattern: '/^[A-Za-zÀ-ÖØ-öø-ÿ\' -]{3,50}$/',
        message: 'Le champ  ne doit contenir que des lettre ou espace - ou bien \''
    )]
    private ?string $city = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'Ce champ  ne peut pa être vide')]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'La rue doit contenir au moins {{ limit }} caractères',
        maxMessage: 'La rue ne peut pas dépasser {{ limit }} caractères'
    )]
    #[Assert\Regex(
        pattern: '/^[A-Za-zÀ-ÖØ-öø-ÿ0-9\' -]{3,50}$/',
        message: 'Le champ  ne doit contenir que des lettre chiffre ou espace - ou bien \''
    )]
    private ?string $street = null;

    #[ORM\Column(length: 2)]
    #[Assert\NotBlank(message: 'Ce champ ne peut pa être vide')]
    #[Assert\Country (message: 'Le champ doit etre en 2 lettres pays')]
    private ?string $country = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'deliveryAddress')]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): static
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addDeliveryAddress($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeDeliveryAddress($this);
        }

        return $this;
    }
}
