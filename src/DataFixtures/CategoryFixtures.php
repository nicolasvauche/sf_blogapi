<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $category = new Category();
        $category->setName('Technologie');
        $manager->persist($category);
        $this->addReference('category-1', $category);

        $category = new Category();
        $category->setName('Sport');
        $manager->persist($category);
        $this->addReference('category-2', $category);

        $category = new Category();
        $category->setName('Musique');
        $manager->persist($category);
        $this->addReference('category-3', $category);

        $manager->flush();
    }

    public function getOrder(): int
    {
        return 2;
    }
}
