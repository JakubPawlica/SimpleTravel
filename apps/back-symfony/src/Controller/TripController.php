<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\User;
use App\Service\TripService;
use App\Repository\UserRepository;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class TripController extends AbstractController
{
    public function __construct(
        private TripService $tripService,
        private UserRepository $userRepository
    ) {}

    #[Route('api/trips', name: 'get_trips', methods: ['GET'])]
    public function getTrips(): JsonResponse
    {
        return $this->json(
            $this->tripService->getAllTrips(),
            200,
            [],
            ['groups' => 'trip:read']
        );
    }

    #[Route('api/trips/{id}', name: 'get_trip_by_id', methods: ['GET'])]
    public function getTripById(int $id): JsonResponse
    {
        $trip = $this->tripService->getTripById($id);

        if(!$trip) {
            throw new NotFoundHttpException('Trip not found');
        }
        return $this->json($trip, 200, [], ['groups' => 'trip:read']);
    }

    #[Route('api/trips', name: 'create_trip', methods: ['POST'])]
    public function createTrip(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if(!isset($data['tripName'], $data['destination'], $data['start_date'], $data['end_date'], $data['description']))
        {
            throw new BadRequestHttpException('Invalid data');
        }
        
        $userId = $request->getSession()->get('user_id');
        if (!$userId) {
            throw new UnauthorizedHttpException('', 'Not authenticated');
        }

        $user = $this->userRepository->find($userId);
        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }

        $trip = $this->tripService->createTrip($data, $user);
        return $this->json($trip, 201, [], ['groups' => 'trip:read']);
    }

    #[Route('api/trips/{id}', name: 'update_trip', methods: ['PUT'])]
    public function updateTrip(int $id, Request $request): JsonResponse
    {
        $trip = $this->tripService->getTripById($id);

        if(!$trip) {
            throw new NotFoundHttpException('Trip not found');
        }

        $data = json_decode($request->getContent(), true);

        if (!isset($data['tripName'], $data['destination'], $data['start_date'], $data['end_date'], $data['description'])) 
        {
            throw new BadRequestHttpException('Invalid data');
        }

        $updatedTrip = $this->tripService->updateTrip($trip, $data);
        return $this->json($trip, 201, [], ['groups' => 'trip:read']);
    }

    #[Route('/api/trips/{id}', name: 'delete_trip', methods: ['DELETE'])]
    public function deleteTrip(int $id, Request $request): JsonResponse
    {
        $trip = $this->tripService->getTripById($id);

        if (!$trip) {
            throw new NotFoundHttpException('Trip not found');
        }

        $userId = $request->getSession()->get('user_id');
        $user = $this->userRepository->find($userId);

        if (!$user || $trip->getCreatedBy()?->getId() !== $user->getId()) {
            throw new AccessDeniedHttpException('Access denied');
        }

        $this->tripService->deleteTrip($trip);
        return $this->json(['message' => 'Trip deleted'], 204);
    }
}
