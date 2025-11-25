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
        $tabCombinaison = [];
        foreach ($tabOrder as $order) {
            foreach ($tabProduct as $product) {
                $tabCombinaison[] = [$order, $product];
            }
        }
        for ($i = 0; $i < 10; $i++) {
            $randomProductOrder = array_rand($tabCombinaison);
            $orderItem = new OrderItem();
            $orderItem->setProduct($tabCombinaison[$randomProductOrder][1]);
            $quantity = $faker->numberBetween(1, 10);
            $orderItem->setQuantity($quantity);
            $orderItem->setUserOrder($tabCombinaison[$randomProductOrder][0]);
            $unitPrice =$tabCombinaison[$randomProductOrder][1]->getPrice();
            $orderItem->setTotal($quantity * $unitPrice);
            $orderItem->setUnitPrice($unitPrice);
            unset($tabCombinaison[$randomProductOrder]);

            $manager->persist($orderItem);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [OrderFixtures::class, ProductFixtures::class];
    }

    public static function getGroups(): array
    {
        return ["test1"];
    }
}
