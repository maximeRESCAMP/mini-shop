<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Order;
use App\Entity\User;
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
        for ($i = 0; $i < 10; $i++) {
            $order = new Order();
            $order->setReference($faker->unique()->ean8());
            $order->setTotal($faker->randomFloat($nbMaxDecimals = 2, $min = 3, $max = 100));
            $order->setUser($faker->randomElement($tabUser));
            $order->setAddress($faker->randomElement($tabAddress));
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
        return ['test1'];
    }
}
