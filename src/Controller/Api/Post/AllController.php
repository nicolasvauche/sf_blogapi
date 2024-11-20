<?php

namespace App\Controller\Api\Post;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/posts', name: 'app_api_post_')]
class AllController extends AbstractController
{
    #[Route('', name: 'all', methods: ['GET'])]
    public function all(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findBy(['published' => true], ['createdAt' => 'DESC']);

        $data = array_map(function(Post $post) {
            return [
                'id' => $post->getId(),
                'title' => $post->getTitle(),
                'updatedAt' => $post->getUpdatedAt()->format('c'),
                'canEdit' => ($this->getUser() === $post->getAuthor()) || $this->isGranted('ROLE_ADMIN'),
                '_links' => [
                    'self' => '/api/posts/detail/' . $post->getSlug(),
                    'author' => '/api/users/' . $post->getAuthor()->getId(),
                    'category' => '/api/categories/' . $post->getCategory()->getSlug(),
                ],
            ];
        }, $posts);

        return $this->json($data, 200);
    }
}
