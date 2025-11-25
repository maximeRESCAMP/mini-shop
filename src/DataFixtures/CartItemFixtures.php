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
        $tabCombinaison =[];
        foreach ($tabUser as $user) {
            foreach ($tabProduct as $product) {
                $tabCombinaison[]=[$user, $product];
            }
        }

        for ($i=0; $i< 10; $i++) {
            $randomProductUser = array_rand($tabCombinaison);
            $cartItem = new CartItem();
            $cartItem->setUser($tabCombinaison[$randomProductUser][0]);
            $cartItem->setProduct($tabCombinaison[$randomProductUser][1]);
            unset($tabCombinaison[$randomProductUser]);
            $cartItem->setQuantity($faker->numberBetween($min = 1, $max = 5));
            $manager->persist($cartItem);
        }

        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [
            ProductFixtures::class,
            UserFixtures::class,
        ];
    }
    public static function getGroups(): array
    {
        return ['test1'];
    }

}
