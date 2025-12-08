<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Address
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'error.not_blank')]
    #[Assert\Country]
    private ?string $country = null;

    #[ORM\Column(length: 5)]
    #[Assert\NotBlank(message: 'error.not_blank')]
    #[Assert\Regex(
        pattern: '/^\d{5}$/',
        message: 'error.regex.address.zip_code'
    )]
    private ?string $zipCode = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'error.not_blank')]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'error.min_length',
        maxMessage: 'error.max_length'
    )]
    #[Assert\Regex(
        pattern: '/^[A-Za-zÀ-ÖØ-öø-ÿ\' -]{3,50}$/',
        message: 'error.regex.address.city'
    )]
    private ?string $city = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'error.not_blank')]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'error.min_length',
        maxMessage: 'error.max_length'
    )]
    #[Assert\Regex(
        pattern: '/^[A-Za-zÀ-ÖØ-öø-ÿ0-9\' ,.’-]{3,50}$/',
        message: 'error.regex.address.street'
    )]
    private ?string $street = null;

    #[ORM\ManyToOne(inversedBy: 'addresses')]
    private ?User $user = null;


    public function getId(): ?int
    {
        return $this->id;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $User): static
    {
        $this->user = $User;

        return $this;
    }
    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        $parameters = $this->getParameters();
        foreach ($parameters as $k => $v) {
            if ($v instanceof TranslatableInterface) {
                $parameters[$k] = $v->trans($translator, $locale);
            }
        }

        return $translator->trans($this->getMessage(), $parameters, $this->getDomain(), $locale);
    }

}
