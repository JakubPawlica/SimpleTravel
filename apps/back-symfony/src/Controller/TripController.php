<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TripController extends AbstractController
{
    private array $trips = [
        1 => ['id' => 1, 'name' => 'Wakacje w Paryzu', 'destination' => 'Francja', 'start_date' => '2024-07-10', 'end_date' => '2024-07-20'],
        2 => ['id' => 2, 'name' => 'Wyjazd do Tokio', 'destination' => 'Japonia', 'start_date' => '2024-09-01', 'end_date' => '2024-09-15'],
    ];

    #[Route('api/trips', name: 'get_trips', methods: ['GET'])]
    public function getTrips(): JsonResponse
    {
        return $this->json(array_values($this->trips), 200);
    }

    #[Route('api/trips/{id}', name: 'get_trip_by_id', methods: ['GET'])]
    public function getTripById(int $id): JsonResponse
    {
        if(!array_key_exists($id, $this->trips)) {
            return $this->json(['error' => 'Trip not found'], 404);
        }

        return $this->json($this->trips[$id], 200);
    }

    #[Route('api/trips', name: 'create_trip', methods: ['POST'])]
    public function createTrip(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if(!isset($data['name'], $data['destination'], $data['start_date'], $data['end_date']));
        {
            return $this->json(['error' => 'Invalid data'], 400);
        }

        $newId = count($this->trips) + 1;
        $newTrip = [
            'id' => $newId,
            'name' => $data['name'],
            'destination' => $data['destination'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date']
        ];
        $this->trips[$newId] = $newTrip;

        return $this->json($newTrip, 201);
    }

    #[Route('api/trips/{id}', name: 'update_trip', methods: ['PUT'])]
    public function updateTrip(int $id, Request $request): JsonResponse
    {
        if(!isset($this->trips[$id])) {
            return $this->json(['error' => 'Trip not found'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if(!isset($data['name'], $data['destination'], $data['start_date'], $data['end_date']));
        {
            return $this->json(['error' => 'Invalid data'], 400);
        }

        $this->trips[$id] = [
            'id' => $id,
            'name' => $data['name'],
            'destination' => $data['destination'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date']
        ];

        return $this->json($this->trips[$id], 201);
    }

    #[Route('/api/trips/{id}', name: 'delete_trip', methods: ['DELETE'])]
    public function deleteTrip(int $id): JsonResponse
    {
        if (!isset($this->trips[$id])) {
            return $this->json(['error' => 'Trip not found'], 404);
        }

        unset($this->trips[$id]);
        return $this->json(['message' => 'Trip deleted'], 204);
    }
}
