<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GroupController extends AbstractController
{
    private array $groups = [
        1 => ['id' => 1, 'name' => 'Ekipa Paryż', 'members' => [1, 2]],
        2 => ['id' => 2, 'name' => 'Podróż do Tokio', 'members' => [2, 3]],
    ];

    #[Route('api/groups/{id}', name: 'get_group_by_id', methods: ['GET'])]
    public function getGroupById(int $id): JsonResponse
    {
        if (!isset($this->groups[$id])) {
            return $this->json(['error' => 'Group not found'], 404);
        }

        return $this->json($this->groups[$id], 200);
    }

    #[Route('api/groups', name: 'create_group', methods: ['POST'])]
    public function createGroup(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['name'])) {
            return $this->json(['error' => 'Group name is required'], 400);
        }

        $newId = count($this->groups) + 1;
        $newGroup = [
            'id' => $newId,
            'name' => $data['name'],
            'members' => []
        ];
        $this->groups[$newId] = $newGroup;

        return $this->json($newGroup, 201);
    }

    #[Route('api/groups/{id}', name: 'update_group', methods: ['PUT'])]
    public function updateGroup(int $id, Request $request): JsonResponse
    {
        if (!isset($this->groups[$id])) {
            return $this->json(['error' => 'Group not found'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (!isset($data['name'])) {
            return $this->json(['error' => 'Group name is required'], 400);
        }

        $this->groups[$id]['name'] = $data['name'];

        return $this->json($this->groups[$id], 200);
    }

    #[Route('/api/groups/{id}', name: 'delete_group', methods: ['DELETE'])]
    public function deleteGroup(int $id): JsonResponse
    {
        if (!isset($this->groups[$id])) {
            return $this->json(['error' => 'Group not found'], 404);
        }

        unset($this->groups[$id]);

        return $this->json(['message' => 'Group deleted'], 204);
    }
}
