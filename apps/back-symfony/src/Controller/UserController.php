<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    private array $users = [
        ['id' => 1, 'name' => 'Jan Kowalski', 'email' => 'jan@example.com'],
        ['id' => 2, 'name' => 'Anna Nowak', 'email' => 'anna@example.com'],
    ];

    #[Route('/api/users', name: 'get_users', methods: ['GET'])]
    public function getUsers(): JsonResponse
    {
        return $this->json($users, 200);
    }

    #[Route('/api/users/{id}', name: 'get_user_by_id', methods: ['GET'])]
    public function getUserById(int $id): JsonResponse
    {
        if (!array_key_exists($id, $users)) {
            return $this->json(['error' => 'User not found'], 404);
        }

        return $this->json($users[$id], 200);
    }

    #[Route('/api/users', name: 'create_user', methods: ['POST'])]
    public function createUser(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['name'], $data['email'])) {
            return $this->json(['error' => 'Invalid data'], 400);
        }

        $newId = count($this->users) + 1;
        $newUser = ['id' => $newId, 'name' => $data['name'], 'email' => $data['email']];
        $this->users[$newId] = $newUser;

        return $this->json($newUser, 201);
    }
}
