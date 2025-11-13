<?php

namespace App\DataFixtures;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class OrderItemFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        $tabOrder = $manager->getRepository(Order::class)->findAll();
        $tabProduct = $manager->getRepository(Product::class)->findAll();
        for ($i = 0; $i < 20; $i++){
            $orderItem = new OrderItem();
            $orderItem->setQuantity($faker->randomDigit());
            $orderItem->setPrice($faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 100));
            $orderItem->setUserOrder($faker->randomElement($tabOrder));
            $orderItem->setProduct($faker->randomElement($tabProduct));
            $manager->persist($orderItem);

        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [OrderFixtures::class, ProductFxtures::class];
    }

    public static function getGroups(): array
    {
       return ["test1"];
    }
}
