<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AddressFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        $tabUser = $manager->getRepository(User::class)->findAll();
        for ($i = 0; $i < 10; $i++) {
            $address = new Address();
            $address->setCountry($faker->country());
            $address->setZipCode(str_replace(' ', "", $faker->postcode()));
            $address->setCity($faker->city());
            $address->setStreet($faker->streetAddress());
            $address->setUser($faker->randomElement($tabUser));
            $manager->persist($address);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['test4'];
    }
}
