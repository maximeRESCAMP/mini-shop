<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture  implements FixtureGroupInterface
{

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordHasher->hashPassword($user, $faker->password));
            $user->setFirstName($faker->firstName);
            $user->setLastName($faker->lastName);
            $user->setPhone(str_replace(" ", '', $faker->phoneNumber));
            $manager->persist($user);
        }

        $userClient = new User();
        $userClient->setEmail('user@hotmail.fr');
        $userClient->setRoles(['ROLE_USER']);
        $userClient->setPassword($this->passwordHasher->hashPassword($userClient, 'user'));
        $userClient->setFirstName($faker->firstName);
        $userClient->setLastName($faker->lastName);
        $userClient->setPhone(str_replace(" ", '', $faker->phoneNumber));
        $manager->persist($userClient);

        $userAdmin = new User();
        $userAdmin->setEmail('admin@hotmail.fr');
        $userAdmin->setRoles(['ROLE_USER','ROLE_ADMIN']);
        $userAdmin->setPassword($this->passwordHasher->hashPassword($userAdmin, 'admin'));
        $userAdmin->setFirstName($faker->firstName);
        $userAdmin->setLastName($faker->lastName);
        $userAdmin->setPhone(str_replace(" ", '', $faker->phoneNumber));
        $manager->persist($userAdmin);
        $manager->flush();
    }

      public static function getGroups(): array
    {
       return ['test1'];
    }
}
