<?php

namespace App\Controller\Api;

use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthController extends AbstractController
{
    #[Route('/auth/login', name: 'api_login', methods: ['POST'])]
    public function login(
        Request                     $request,
        UserPasswordHasherInterface $passwordHasher,
        JWTTokenManagerInterface    $jwtManager,
        UserRepository              $userRepository
    ): Response
    {
        $data = json_decode($request->getContent(), true);

        if(!$data || !isset($data['email'], $data['password'])) {
            return $this->json(['error' => 'Identifiants incorrects'], Response::HTTP_BAD_REQUEST);
        }

        $user = $userRepository->findOneBy(['email' => $data['email']]);

        if(!$user || !$passwordHasher->isPasswordValid($user, $data['password'])) {
            return $this->json(['error' => 'Identifiants incorrects'], Response::HTTP_UNAUTHORIZED);
        }

        $token = $jwtManager->create($user);

        return $this->json([
            'message' => 'Login successful',
            'token' => $token,
        ]);
    }
}
