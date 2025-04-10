<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Entity\User;
use App\Service\AuthService;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class AuthController extends AbstractController
{
    public function __construct(
        private AuthService $authService
    ) {}

    #[Route('/api/register', name: 'register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $user = $this->authService->register($data);
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

        $user = $this->authService->validateCredentials($data['email'], $data['password']);
        if (!$user) {
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
    public function me(SessionInterface $session): JsonResponse
    {
        $userId = $session->get('user_id');

        if (!$userId) {
            return $this->json(['error' => 'Not logged in'], 401);
        }

        $user = $this->authService->getUserById($userId);
        return $this->json($user, 200, [], ['groups' => 'user:read']);
    }
}
