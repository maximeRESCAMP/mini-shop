<?php

namespace App\Service;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

readonly class UserService
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function insertUser(User $user):void
    {
        $this->em->persist($user);
        $this->em->flush();
    }

}
