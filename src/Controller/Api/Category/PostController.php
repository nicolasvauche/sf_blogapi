<?php

namespace App\Controller\Api\Category;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/categories', name: 'app_api_category_')]
class PostController extends AbstractController
{
    #[Route('/{slug}/posts', name: 'posts', methods: ['GET'])]
    public function posts(Category $category): Response
    {
        $data = [
            '_links' => [
                'self' => '/api/categories/' . $category->getSlug() . '/posts',
                'category' => '/api/categories/' . $category->getSlug(),
            ],
            '_embedded' => [
                'posts' => $category->getPosts()->map(function($post) {
                    return [
                        'id' => $post->getId(),
                        'title' => $post->getTitle(),
                        'slug' => $post->getSlug(),
                        'canEdit' => ($this->getUser() === $post->getAuthor()) || $this->isGranted('ROLE_ADMIN'),
                        '_links' => [
                            'self' => '/api/posts/detail/' . $post->getSlug(),
                        ],
                    ];
                })->toArray(),
            ],
        ];

        return $this->json($data, 200);
    }
}
