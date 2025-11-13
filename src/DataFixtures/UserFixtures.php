<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture  implements FixtureGroupInterface, DependentFixtureInterface
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
        $tabAdress = $manager->getRepository(Address::class)->findAll();
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setLastName($faker->lastName);
            $user->setPhone(str_replace(" ", '', $faker->phoneNumber));
            $user->setPassword($this->passwordHasher->hashPassword($user, $faker->password));
            $user->setFirstName($faker->firstName);
            $user->addDeliveryAddress($faker->randomElement($tabAdress));
            $manager->persist($user);
        }

        $userClient = new User();
        $userClient->setEmail('user@hotmail.fr');
        $userClient->setLastName($faker->lastName);
        $userClient->setPhone(str_replace(" ", '', $faker->phoneNumber));
        $userClient->setPassword($this->passwordHasher->hashPassword($user, 'user'));
        $userClient->setFirstName($faker->firstName);
        $userClient->setRoles(['ROLE_USER']);
        $userClient->addDeliveryAddress($faker->randomElement($tabAdress));
        $manager->persist($userClient);

        $userAdmin = new User();
        $userAdmin->setEmail('admin@hotmail.fr');
        $userAdmin->setLastName($faker->lastName);
        $userAdmin->setPhone(str_replace(" ", '', $faker->phoneNumber));
        $userAdmin->setPassword($this->passwordHasher->hashPassword($user, 'admin'));
        $userAdmin->setFirstName($faker->firstName);
        $userAdmin->setRoles(['ROLE_USER','ROLE_ADMIN']);
        $userAdmin->addDeliveryAddress($faker->randomElement($tabAdress));
        $manager->persist($userAdmin);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AddressFixtures::class,
        ];
    }


    public static function getGroups(): array
    {
       return ['test1'];
    }
}
