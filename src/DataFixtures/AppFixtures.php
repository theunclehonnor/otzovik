<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Пытался я тут настроить шифрование с UserPasswordEncoderInterface, но так и не вышло
        // Create Admin
         $user = (new User())
            ->setEmail('admin@mail.ru')
            ->setPassword('admin')
            ->setRoles(["ROLE_ADMIN"]);
         $manager->persist($user);
         // Create User
        $user = (new User())
            ->setEmail('user@mail.ru')
            ->setPassword('user')
            ->setRoles(["ROLE_USER"]);
        $manager->persist($user);
        $manager->flush();
    }
}
