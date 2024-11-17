<?php

namespace App\Controller\Api\Category;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/categories', name: 'app_api_category_')]
class AllController extends AbstractController
{
    #[Route('', name: 'all', methods: ['GET'])]
    public function all(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        $data = array_map(function(Category $category) {
            return [
                'id' => $category->getId(),
                'name' => $category->getName(),
                'slug' => $category->getSlug(),
                '_links' => [
                    'self' => '/api/categories/' . $category->getSlug(),
                    'posts' => '/api/categories/' . $category->getSlug() . '/posts',
                ],
            ];
        }, $categories);

        return $this->json($data, 200);
    }
}
