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
    #[Route('/{slug}', name: 'detail', methods: ['GET'])]
    public function detail(User $user, SerializerInterface $serializer): Response
    {
        $data = $serializer->normalize($user, null, ['groups' => 'user:detail']);

        return $this->json($data, 200);
    }
}
