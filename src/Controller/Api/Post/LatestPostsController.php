<?php

namespace App\Controller\Api\Post;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/posts', name: 'app_api_post_')]
class LatestPostsController extends AbstractController
{
    #[Route('/latest', name: 'latest', methods: ['GET'])]
    public function latest(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findBy(
            ['published' => true],
            ['createdAt' => 'DESC'],
            3
        );

        $data = array_map(function($post) {
            return [
                'id' => $post->getId(),
                'title' => $post->getCategory()->getName() . ' - ' . $post->getTitle(),
                'slug' => $post->getSlug(),
                'updatedAt' => $post->getUpdatedAt()->format('c'),
                'canEdit' => ($this->getUser() === $post->getAuthor()) || $this->isGranted('ROLE_ADMIN'),
                '_links' => [
                    'self' => '/api/posts/detail/' . $post->getSlug(),
                ],
            ];
        }, $posts);

        return $this->json($data, 200);
    }
}
