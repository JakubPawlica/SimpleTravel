<?php

namespace App\Service;

use App\Entity\Trip;
use App\Entity\User;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManagerInterface;

class TripService
{
    public function __construct(
        private EntityManagerInterface $em,
        private TripRepository $tripRepository
    ) {}

    public function getAllTrips(): array
    {
        return $this->tripRepository->findAll();
    }

    public function getTripById(int $id): ?Trip
    {
        return $this->tripRepository->find($id);
    }

    public function createTrip(array $data, User $user): Trip
    {
        $trip = new Trip();
        $trip->setTripName($data['tripName']);
        $trip->setDestination($data['destination']);
        $trip->setStartDate(new \DateTime($data['start_date']));
        $trip->setEndDate(new \DateTime($data['end_date']));
        $trip->setDescription($data['description']);
        $trip->setCreatedBy($user);

        $this->em->persist($trip);
        $this->em->flush();

        return $trip;
    }

    public function updateTrip(Trip $trip, array $data): Trip
    {
        $trip->setTripName($data['tripName']);
        $trip->setDestination($data['destination']);
        $trip->setStartDate(new \DateTime($data['start_date']));
        $trip->setEndDate(new \DateTime($data['end_date']));
        $trip->setDescription($data['description']);

        $this->em->flush();
        return $trip;
    }

    public function deleteTrip(Trip $trip): void
    {
        $this->em->remove($trip);
        $this->em->flush();
    }
}
