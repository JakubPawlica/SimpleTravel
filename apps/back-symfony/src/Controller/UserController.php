<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    private array $users = [
        1 => ['id' => 1, 'name' => 'Jan Kowalski', 'email' => 'jan@example.com'],
        2 => ['id' => 2, 'name' => 'Anna Nowak', 'email' => 'anna@example.com'],
    ];

    #[Route('/api/users', name: 'get_users', methods: ['GET'])]
    public function getUsers(): JsonResponse
    {
        return $this->json(array_values($this->users), 200);
    }

    #[Route('/api/users/{id}', name: 'get_user_by_id', methods: ['GET'])]
    public function getUserById(int $id): JsonResponse
    {
        if (!array_key_exists($id, $this->users)) {
            return $this->json(['error' => 'User not found'], 404);
        }

        return $this->json($this->users[$id], 200);
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

    #[Route('/api/users/{id}', name: 'update_user', methods: ['PUT'])]
    public function updateUser(int $id, Request $request): JsonResponse
    {
        if (!isset($this->users[$id])) {
            return $this->json(['error' => 'User not found'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (!isset($data['name'], $data['email'])) {
            return $this->json(['error' => 'Invalid data'], 400);
        }

        $this->users[$id] = ['id' => $id, 'name' => $data['name'], 'email' => $data['email']];
        return $this->json($this->users[$id], 200);
    }

    #[Route('/api/users/{id}', name: 'delete_user', methods: ['DELETE'])]
    public function deleteUser(int $id): JsonResponse
    {
        if (!isset($this->users[$id])) {
            return $this->json(['error' => 'User not found'], 404);
        }

        unset($this->users[$id]);
        return $this->json(['message' => 'User deleted'], 204);
    }
}
