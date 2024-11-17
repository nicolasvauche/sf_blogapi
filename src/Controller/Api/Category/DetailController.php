<?php

namespace App\Controller\Api\Category;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/categories', name: 'app_api_category_')]
class DetailController extends AbstractController
{
    #[Route('/{slug}', name: 'detail', methods: ['GET'])]
    public function detail(Category $category): Response
    {
        $data = [
            'id' => $category->getId(),
            'name' => $category->getName(),
            'slug' => $category->getSlug(),
            '_links' => [
                'self' => '/api/categories/' . $category->getSlug(),
                'posts' => '/api/categories/' . $category->getSlug() . '/posts',
            ],
        ];

        return $this->json($data, 200);
    }
}
