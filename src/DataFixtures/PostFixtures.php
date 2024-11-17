<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PostFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $categories = [
            'category-1' => 'Technologie',
            'category-2' => 'Sport',
            'category-3' => 'Musique',
        ];

        foreach($categories as $categoryReference => $categoryName) {
            for($i = 1; $i <= 3; $i++) {
                $post = new Post();
                $post->setCategory($this->getReference($categoryReference))
                    ->setTitle("$categoryName - Article $i")
                    ->setContent("Contenu de l'article $i dans la catégorie $categoryName.")
                    ->setAuthor($this->getReference($i % 2 === 0 ? 'user-author-2' : 'user-author-1'))
                    ->setPublished(true);

                $manager->persist($post);
                $this->addReference("post-$categoryReference-$i", $post);

                usleep(1000000);
            }
        }

        $manager->flush();
    }

    public function getOrder(): int
    {
        return 3;
    }
}
