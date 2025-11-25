<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ProductFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        $tabCategory = $manager->getRepository(Category::class)->findAll();
        for ($i = 0; $i < 10; $i++) {
            $product = new Product();
            $product->setName($faker->unique()->words(2, true));
            $product->setSlug($faker->unique()->slug(2));
            $product->setDescription($faker->text(200));
            $product->setPrice($faker->randomFloat(2,1,100));
            $product->setStock($faker->numberBetween(1, 100));
            $product->setPicture($faker->imageUrl());
            $product->setCategory($faker->randomElement($tabCategory));
            $manager->persist($product);
        }

        $manager->flush();


    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['test2'];
    }
}
