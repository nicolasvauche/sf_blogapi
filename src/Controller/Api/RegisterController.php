<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractController
{
    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(
        Request                     $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface      $entityManager,
        UserRepository              $userRepository
    ): Response
    {
        $data = json_decode($request->getContent(), true);

        if(!$data || !isset($data['name'], $data['email'], $data['password'])) {
            return $this->json(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
        }

        if($userRepository->findOneBy(['email' => $data['email']])) {
            return $this->json(['error' => 'Email already in use'], Response::HTTP_CONFLICT);
        }

        $user = new User();
        $user->setName($data['name'])
            ->setEmail($data['email'])
            ->setPassword($passwordHasher->hashPassword($user, $data['password']))
            ->setActive(true);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json(['message' => 'User registered successfully'], Response::HTTP_CREATED);
    }
}
