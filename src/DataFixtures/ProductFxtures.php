<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ProductFxtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        $tabProduct = $manager->getRepository(Category::class)->findAll();

        for ($i = 0; $i < 20; $i++) {
            $product = new Product();
            $product->setName($faker->words(1, true));
            $product->setStock($faker->numberBetween(0, 100));
            $product->setPrice($faker->randomFloat());
            $product->setDescription($faker->text(200));
            $product->setImage($faker->imageUrl());
            $product->setSlug($faker->slug(3));
            $product->addCategory($faker->randomElement($tabProduct));
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
}
