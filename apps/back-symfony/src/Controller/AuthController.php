<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class AuthController extends AbstractController
{
    #[Route('/api/register', name: 'register', methods: ['POST'])]
    public function register(Request $request, EntityManagerInterface $em, UserRepository $userRepository): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            if (!isset($data['name'], $data['email'], $data['password'])) {
                return $this->json(['error' => 'Invalid data'], 400);
            }
    
            if ($userRepository->findOneBy(['email' => $data['email']])) {
                return $this->json(['error' => 'Podany email jest już zajęty'], 409);
            }
    
            $user = new User();
            $user->setName($data['name']);
            $user->setEmail($data['email']);
            $user->setPasswordHash(password_hash($data['password'], PASSWORD_DEFAULT));
            $user->setSessionToken(bin2hex(random_bytes(32)));
    
            $em->persist($user);
            $em->flush();
    
            return $this->json(['message' => 'User registered'], 201);
        } catch (\Throwable $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/api/login', name: 'login', methods: ['POST'])]
    public function login(Request $request, SessionInterface $session, UserRepository $userRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['email'], $data['password'])) {
            return $this->json(['error' => 'Invalid credentials'], 400);
        }

        $user = $userRepository->findOneBy(['email' => $data['email']]);
        if (!$user || !password_verify($data['password'], $user->getPasswordHash())) {
            return $this->json(['error' => 'Niepoprawne dane logowania'], 401);
        }

        $session->set('user_id', $user->getId());

        return $this->json(['message' => 'Logged in']);
    }

    #[Route('/api/logout', name: 'logout', methods: ['POST'])]
    public function logout(SessionInterface $session): JsonResponse
    {
        $session->clear();
        return $this->json(['message' => 'Logged out']);
    }

    #[Route('/api/me', name: 'me', methods: ['GET'])]
    public function me(SessionInterface $session, UserRepository $userRepository): JsonResponse
    {
        $userId = $session->get('user_id');

        if (!$userId) {
            return $this->json(['error' => 'Not logged in'], 401);
        }

        $user = $userRepository->find($userId);
        return $this->json($user);
    }
}
