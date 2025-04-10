<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Service\TripService;
use App\Entity\Trip;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class TripServiceTest extends TestCase
{
    private TripService $tripService;
    private $em;
    private $userRepository;
    private $tripRepository;

    protected function setUp(): void
    {
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->tripRepository = $this->createMock(TripRepository::class);

        $this->em
            ->method('getRepository')
            ->with(User::class)
            ->willReturn($this->userRepository);

        $this->tripService = new TripService($this->em, $this->tripRepository);
    }

    public function testGetAllTripsSuccess(): void
    {
        $trips = [new Trip(), new Trip()];

        $this->tripRepository
            ->method('findAll')
            ->willReturn($trips);

        $result = $this->tripService->getAllTrips();

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf(Trip::class, $result);
    }

    public function testGetTripByIdSuccess(): void
    {
        $trip = new Trip();

        $this->tripRepository
            ->method('find')
            ->with(1)
            ->willReturn($trip);

        $result = $this->tripService->getTripById(1);
        $this->assertInstanceOf(Trip::class, $result);
    }

    public function testGetTripByIdFail(): void
    {
        $this->tripRepository
            ->method('find')
            ->with(999)
            ->willReturn(null);

        $result = $this->tripService->getTripById(999);
        $this->assertNull($result);
    }

    public function testCreateTripSuccess(): void
    {
        $data = [
            'tripName' => 'Test Trip',
            'destination' => 'Paris',
            'start_date' => '2025-06-01',
            'end_date' => '2025-06-07',
            'description' => 'Trip to Paris'
        ];

        $user = new User();

        $this->em
            ->expects($this->once())
            ->method('persist');

        $this->em
            ->expects($this->once())
            ->method('flush');

        $trip = $this->tripService->createTrip($data, $user);

        $this->assertInstanceOf(Trip::class, $trip);
        $this->assertSame('Test Trip', $trip->getTripName());
    }

    public function testUpdateTripSuccess(): void
    {
        $trip = new Trip();
        $trip->setTripName('Stara nazwa');
        $trip->setDestination('Stare miejsce');
        $trip->setStartDate(new \DateTime('2025-01-01'));
        $trip->setEndDate(new \DateTime('2025-01-10'));
        $trip->setDescription('Stary opis');

        $data = [
            'tripName' => 'Nowa wycieczka',
            'destination' => 'Nowy Jork',
            'start_date' => '2025-06-01',
            'end_date' => '2025-06-07',
            'description' => 'Opis nowej wycieczki'
        ];

        $this->em
            ->expects($this->once())
            ->method('flush');

        $updatedTrip = $this->tripService->updateTrip($trip, $data);

        $this->assertSame('Nowa wycieczka', $updatedTrip->getTripName());
        $this->assertSame('Nowy Jork', $updatedTrip->getDestination());
        $this->assertEquals(new \DateTime('2025-06-01'), $updatedTrip->getStartDate());
        $this->assertEquals(new \DateTime('2025-06-07'), $updatedTrip->getEndDate());
        $this->assertSame('Opis nowej wycieczki', $updatedTrip->getDescription());
    }

    public function testDeleteTripSuccess(): void
    {
        $trip = new Trip();

        $this->em
            ->expects($this->once())
            ->method('remove')
            ->with($trip);

        $this->em
            ->expects($this->once())
            ->method('flush');

        $this->tripService->deleteTrip($trip);

        $this->assertTrue(true);
    }

}
