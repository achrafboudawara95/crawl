<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture implements FixtureGroupInterface
{
    private UserPasswordHasherInterface $passwordHasher;
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setPassword($this->passwordHasher->hashPassword(
            $admin,
            'admin'
        ));
        $admin->setRoles([USER::ROLE_ADMIN]);
        $manager->persist($admin);

        $moderator = new User();
        $moderator->setUsername('moderator');
        $moderator->setPassword($this->passwordHasher->hashPassword(
            $admin,
            'moderator'
        ));
        $manager->persist($moderator);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['users'];
    }
}
