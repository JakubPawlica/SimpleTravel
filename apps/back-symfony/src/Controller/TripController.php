<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TripController extends AbstractController
{
    private array $trips = [
        1 => ['id' => 1, 'name' => 'Wakacje w Paryżu', 'destination' => 'Francja', 'start_date' => '2024-07-10', 'end_date' => '2024-07-20'],
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
}
