<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements OrderedFixtureInterface
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('admin@bloagpi.com')
            ->setPassword($this->passwordHasher->hashPassword($user, 'admin'))
            ->setRoles(['ROLE_ADMIN'])
            ->setName('Administrateur')
            ->setActive(true);
        $manager->persist($user);
        $this->addReference('user-admin', $user);

        $user = new User();
        $user->setEmail('author@bloagpi.com')
            ->setPassword($this->passwordHasher->hashPassword($user, 'author'))
            ->setName('Auteur 1')
            ->setActive(true);
        $manager->persist($user);
        $this->addReference('user-author-1', $user);

        $user = new User();
        $user->setEmail('author2@bloagpi.com')
            ->setPassword($this->passwordHasher->hashPassword($user, 'author'))
            ->setName('Auteur 2')
            ->setActive(true);
        $manager->persist($user);
        $this->addReference('user-author-2', $user);

        $manager->flush();
    }

    public function getOrder(): int
    {
        return 1;
    }
}
