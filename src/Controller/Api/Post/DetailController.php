<?php

namespace App\Controller\Api\Post;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/posts', name: 'app_api_post_')]
class DetailController extends AbstractController
{
    #[Route('/detail/{slug}', name: 'detail', methods: ['GET'])]
    public function detail(Post $post): Response
    {
        $data = [
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'content' => $post->getContent(),
            'updatedAt' => $post->getUpdatedAt()->format('c'),
            '_links' => [
                'self' => '/api/posts/detail/' . $post->getSlug(),
                'author' => '/api/users/' . $post->getAuthor()->getSlug(),
                'category' => '/api/categories/' . $post->getCategory()->getSlug(),
            ],
        ];

        return $this->json($data);
    }
}
