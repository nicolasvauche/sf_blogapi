<?php

namespace App\Controller\Api\Post;

use App\Entity\Post;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/posts', name: 'app_api_post_')]
class AddController extends AbstractController
{
    #[Route('/add', name: 'add', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function add(
        Request                $request,
        EntityManagerInterface $entityManager,
        CategoryRepository     $categoryRepository
    ): Response
    {
        $data = json_decode($request->getContent(), true);

        if(!$data || !isset($data['title'], $data['content'], $data['category_id'])) {
            return $this->json(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
        }

        $category = $categoryRepository->find($data['category_id']);

        if(!$category) {
            return $this->json(['error' => 'Category not found'], Response::HTTP_BAD_REQUEST);
        }

        $post = new Post();
        $post->setTitle($data['title'])
            ->setContent($data['content'])
            ->setPublished(true)
            ->setAuthor($this->getUser())
            ->setCategory($category);

        $entityManager->persist($post);
        $entityManager->flush();

        return $this->json(['message' => 'Post created successfully'], Response::HTTP_CREATED);
    }
}
