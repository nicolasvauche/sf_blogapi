<?php

namespace App\Controller\Api\Post;

use App\Entity\Post;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/posts', name: 'app_api_post_')]
class EditController extends AbstractController
{
    #[Route('/edit/{slug}', name: 'edit', methods: ['PUT'])]
    #[IsGranted('ROLE_USER')]
    public function edit(
        Post                   $post,
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

        if($post->getAuthor() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['error' => 'Access denied'], Response::HTTP_FORBIDDEN);
        }

        $post->setTitle($data['title'])
            ->setContent($data['content'])
            ->setCategory($category);

        $entityManager->flush();

        return $this->json(['message' => 'Post updated successfully'], Response::HTTP_OK);
    }
}
