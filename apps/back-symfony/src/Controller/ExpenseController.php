<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ExpenseController extends AbstractController
{
    private array $expenses = [
        1 => ['id' => 1, 'group_id' => 1, 'payer' => 1, 'amount' => 100.00, 'description' => 'Hotel w Paryżu', 'split_between' => [1, 2]],
        2 => ['id' => 2, 'group_id' => 2, 'payer' => 2, 'amount' => 50.00, 'description' => 'Kolacja w Tokio', 'split_between' => [2, 3]],
    ];

    #[Route('api/expenses', name: 'create_expense', methods: ['POST'])]
    public function createExpense(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['group_id'], $data['payer'], $data['amount'], $data['description'], $data['split_between'])) {
            return $this->json(['error' => 'Invalid expense data'], 400);
        }

        $newId = count($this->expenses) + 1;
        $newExpense = [
            'id' => $newId,
            'group_id' => $data['group_id'],
            'payer' => $data['payer'],
            'amount' => (float) $data['amount'],
            'description' => $data['description'],
            'split_between' => $data['split_between']
        ];
        $this->expenses[$newId] = $newExpense;

        return $this->json($newExpense, 201);
    }

    #[Route('api/expenses/{id}', name: 'get_expense_by_id', methods: ['GET'])]
    public function getExpenseById(int $id): JsonResponse
    {
        if (!isset($this->expenses[$id])) {
            return $this->json(['error' => 'Expense not found'], 404);
        }

        return $this->json($this->expenses[$id], 200);
    }

    #[Route('api/expenses/group/{groupId}', name: 'get_expenses_by_group', methods: ['GET'])]
    public function getExpensesByGroup(int $groupId): JsonResponse
    {
        $filteredExpenses = array_filter($this->expenses, fn($expense) => $expense['group_id'] === $groupId);

        return $this->json(array_values($filteredExpenses), 200);
    }

    #[Route('api/expenses/{id}', name: 'update_expense', methods: ['PUT'])]
    public function updateExpense(int $id, Request $request): JsonResponse
    {
        if (!isset($this->expenses[$id])) {
            return $this->json(['error' => 'Expense not found'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (!isset($data['group_id'], $data['payer'], $data['amount'], $data['description'], $data['split_between'])) {
            return $this->json(['error' => 'Invalid expense data'], 400);
        }

        $this->expenses[$id] = [
            'id' => $id,
            'group_id' => $data['group_id'],
            'payer' => $data['payer'],
            'amount' => (float) $data['amount'],
            'description' => $data['description'],
            'split_between' => $data['split_between']
        ];

        return $this->json($this->expenses[$id], 200);
    }

    #[Route('api/expenses/{id}', name: 'delete_expense', methods: ['DELETE'])]
    public function deleteExpense(int $id): JsonResponse
    {
        if (!isset($this->expenses[$id])) {
            return $this->json(['error' => 'Expense not found'], 404);
        }

        unset($this->expenses[$id]);

        return $this->json(['message' => 'Expense deleted'], 204);
    }
}
