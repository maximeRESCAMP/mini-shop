<?php

namespace App\DataFixtures;

use App\Entity\Address;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
class AddressFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i=0; $i<20; $i++){
            $address= new Address();
            $address->setCity($faker->city);
            $address->setCountry($faker->countryCode);
            $address->setZipCode(str_replace(' ',"",$faker->postcode));
            $address->setStreet($faker->streetAddress);
            $manager->persist($address);
        }
        $manager->flush();
    }
}
