<?php

namespace App\Controller\Api\User;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/users', name: 'app_api_user_')]
class DetailController extends AbstractController
{
    #[Route('/details/{slug}', name: 'detail', methods: ['GET'])]
    public function detail(User $user, SerializerInterface $serializer): Response
    {
        $data = $serializer->normalize($user, null, ['groups' => 'user:detail']);

        return $this->json($data, 200);
    }

    #[Route('/me', name: 'me', methods: ['GET'])]
    public function me(): Response
    {
        $user = $this->getUser();

        if(!$user) {
            return $this->json(['message' => 'Non authentifié'], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
        ]);
    }
}
