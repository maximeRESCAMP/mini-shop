<?php

namespace App\DataFixtures;

use App\Entity\CartItem;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CartItemFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        $tabUser = $manager->getRepository(User::class)->findAll();
        $tabProduct = $manager->getRepository(Product::class)->findAll();

        for ($i=0; $i< 20; $i++) {
            $cartItem = new CartItem();
            $cartItem->setQuantity($faker->numberBetween($min = 1, $max = 10));
            $cartItem->setUser($faker->randomElement($tabUser));
            $cartItem->setProduct($faker->randomElement($tabProduct));
            $manager->persist($cartItem);

        }

        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [
            ProductFxtures::class,
            UserFixtures::class,
        ];
    }
    public static function getGroups(): array
    {
        return ['test1'];
    }


}
