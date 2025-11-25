<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Order;
use App\Entity\User;
use App\Enum\OrderStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class OrderFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        $tabUser = $manager->getRepository(User::class)->findAll();
        $tabAddress = $manager->getRepository(Address::class)->findAll();
        $tabStatut = OrderStatus::cases();
        for ($i = 0; $i < 10; $i++) {
            $order = new Order();
            $order->setUser($faker->randomElement($tabUser));
            $order->setReference($faker->unique()->ean8());
            $order->setAddress($faker->randomElement($tabAddress));
            $order->setTotal($faker->randomFloat($nbMaxDecimals = 2, $min = 3, $max = 100));
            $order->setStatus($faker->randomElement($tabStatut));
            $manager->persist($order);

        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return[
            AddressFixtures::class,
            UserFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['test3'];
    }
}
