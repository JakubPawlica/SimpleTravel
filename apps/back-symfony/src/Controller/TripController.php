<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\User;
use App\Entity\Trip;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManagerInterface;

class TripController extends AbstractController
{
    #[Route('api/trips', name: 'get_trips', methods: ['GET'])]
    public function getTrips(TripRepository $tripRepository): JsonResponse
    {
        $trips = $tripRepository->findAll();
        return $this->json($trips, 200, [], ['groups' => 'trip:read']);
    }

    #[Route('api/trips/{id}', name: 'get_trip_by_id', methods: ['GET'])]
    public function getTripById(int $id, TripRepository $tripRepository): JsonResponse
    {
        $trip = $tripRepository->find($id);

        if(!$trip) {
            return $this->json(['error' => 'Trip not found'], 404);
        }

        return $this->json($trip, 200, [], ['groups' => 'trip:read']);
    }

    #[Route('api/trips', name: 'create_trip', methods: ['POST'])]
    public function createTrip(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if(!isset($data['tripName'], $data['destination'], $data['start_date'], $data['end_date'], $data['description']))
        {
            return $this->json(['error' => 'Invalid data'], 400);
        }

        $trip = new Trip();
        $trip->setTripName($data['tripName']);
        $trip->setDestination($data['destination']);
        $trip->setStartDate(new \DateTime($data['start_date']));
        $trip->setEndDate(new \DateTime($data['end_date']));
        $trip->setDescription($data['description']);
        
        $userId = $request->getSession()->get('user_id');
        if (!$userId) {
            return $this->json(['error' => 'Not authenticated'], 401);
        }

        $user = $em->getRepository(User::class)->find($userId);
        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        $trip->setCreatedBy($user);

        $em->persist($trip);
        $em->flush();

        return $this->json($trip, 201, [], ['groups' => 'trip:read']);
    }

    #[Route('api/trips/{id}', name: 'update_trip', methods: ['PUT'])]
    public function updateTrip(int $id, Request $request, TripRepository $tripRepository, EntityManagerInterface $em): JsonResponse
    {
        $trip = $tripRepository->find($id);

        if(!$trip) {
            return $this->json(['error' => 'Trip not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (!isset($data['tripName'], $data['destination'], $data['start_date'], $data['end_date'], $data['description'])) 
        {
            return $this->json(['error' => 'Invalid data'], 400);
        }

        $trip->setTripName($data['tripName']);
        $trip->setDestination($data['destination']);
        $trip->setStartDate(new \DateTime($data['start_date']));
        $trip->setEndDate(new \DateTime($data['end_date']));
        $trip->setDescription($data['description']);

        $em->flush();

        return $this->json($trip, 201, [], ['groups' => 'trip:read']);
    }

    #[Route('/api/trips/{id}', name: 'delete_trip', methods: ['DELETE'])]
    public function deleteTrip(int $id, Request $request, TripRepository $tripRepository, EntityManagerInterface $em): JsonResponse
    {
        $trip = $tripRepository->find($id);

        if (!$trip) {
            return $this->json(['error' => 'Trip not found'], 404);
        }

        $userId = $request->getSession()->get('user_id');
        $currentUser = $em->getRepository(User::class)->find($userId);

        if (!$currentUser || $trip->getCreatedBy()?->getId() !== $currentUser->getId()) {
            return $this->json(['error' => 'Access denied'], 403);
        }

        $em->remove($trip);
        $em->flush();

        return $this->json(['message' => 'Trip deleted'], 204);
    }
}
