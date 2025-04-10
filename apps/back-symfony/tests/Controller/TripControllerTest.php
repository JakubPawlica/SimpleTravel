<?php

namespace App\Tests\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Tests\DatabaseTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\User;
use App\Entity\Trip;

class TripControllerTest extends DatabaseTestCase
{
    private $entityManager;
    private $client;
    private $testUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get('doctrine')->getManager();

        $user = new User();
        $user->setName('Testowy Użytkownik');
        $user->setEmail('test@example.com');
        $user->setPasswordHash('fake_hash');
        $user->setSessionToken(bin2hex(random_bytes(16)));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->testUser = $user;

        $this->loginTestUser();
    }

    private function loginTestUser(): void
    {
        $session = static::getContainer()->get(SessionInterface::class);

        $session->set('user_id', $this->testUser->getId());
        $session->save();

        $this->client->getCookieJar()->set(
            new Cookie($session->getName(), $session->getId())
        );
    }

    public function testGetTripsReturns200(): void
    {
        $this->client->request('GET', '/api/trips');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }

    public function testCreateTripReturns201(): void
    {
        $this->client->request('POST', '/api/trips', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'tripName' => 'Test Trip',
            'destination' => 'Berlin',
            'start_date' => '2025-01-01',
            'end_date' => '2025-01-05',
            'description' => 'Wycieczka testowa'
        ]));

        $this->assertResponseStatusCodeSame(201);
    }

    public function testCreateTripReturns400WithInvalidData(): void
    {
        $this->client->request('POST', '/api/trips', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'tripName' => 'Pusta podróż'
        ]));

        $this->assertResponseStatusCodeSame(400);
    }

    public function testGetTripReturns200(): void
    {
        $trip = new Trip();
        $trip->setTripName('Test trip');
        $trip->setDestination('Paris');
        $trip->setStartDate(new \DateTime('2025-01-01'));
        $trip->setEndDate(new \DateTime('2025-01-10'));
        $trip->setDescription('Opis wycieczki');
        $trip->setCreatedBy($this->testUser);

        $this->entityManager->persist($trip);
        $this->entityManager->flush();

        $this->client->request('GET', '/api/trips/' . $trip->getId());

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }

    public function testGetTripReturns404(): void
    {
        $this->client->request('GET', '/api/trips/99999');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testUpdateTripReturns201(): void
    {
        $trip = new Trip();
        $trip->setTripName('Do edycji');
        $trip->setDestination('Rzym');
        $trip->setStartDate(new \DateTime('2025-04-01'));
        $trip->setEndDate(new \DateTime('2025-04-07'));
        $trip->setDescription('Zaraz zmienimy');
        $trip->setCreatedBy($this->testUser);

        $this->entityManager->persist($trip);
        $this->entityManager->flush();

        $this->client->request('PUT', '/api/trips/' . $trip->getId(), [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'tripName' => 'Zmieniona',
            'destination' => 'Rzym',
            'start_date' => '2025-04-02',
            'end_date' => '2025-04-08',
            'description' => 'Zmieniono'
        ]));

        $this->assertResponseStatusCodeSame(201);
    }

    public function testUpdateTripReturns404(): void
    {
        $this->client->request('PUT', '/api/trips/99999', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'tripName' => 'Nie istnieje',
            'destination' => 'X',
            'start_date' => '2025-01-01',
            'end_date' => '2025-01-02',
            'description' => 'X'
        ]));

        $this->assertResponseStatusCodeSame(404);
    }

    public function testDeleteTripReturns204(): void
    {
        $trip = new Trip();
        $trip->setTripName('Do usunięcia');
        $trip->setDestination('Londyn');
        $trip->setStartDate(new \DateTime('2025-05-01'));
        $trip->setEndDate(new \DateTime('2025-05-05'));
        $trip->setDescription('Zostanie usunięta');
        $trip->setCreatedBy($this->testUser);

        $this->entityManager->persist($trip);
        $this->entityManager->flush();

        $this->client->request('DELETE', '/api/trips/' . $trip->getId());

        $this->assertResponseStatusCodeSame(204);
    }

    public function testDeleteTripReturns403ForDifferentUser(): void
    {
        $otherUser = new User();
        $otherUser->setName('Inny');
        $otherUser->setEmail('inny@example.com');
        $otherUser->setPasswordHash('xxx');
        $otherUser->setSessionToken('xxx');

        $this->entityManager->persist($otherUser);

        $trip = new Trip();
        $trip->setTripName('Nie Twoja');
        $trip->setDestination('Nowy Jork');
        $trip->setStartDate(new \DateTime('2025-07-01'));
        $trip->setEndDate(new \DateTime('2025-07-10'));
        $trip->setDescription('Tego nie usuniesz');
        $trip->setCreatedBy($otherUser);

        $this->entityManager->persist($trip);
        $this->entityManager->flush();

        $this->client->request('DELETE', '/api/trips/' . $trip->getId());

        $this->assertResponseStatusCodeSame(403);
    }

    public function testDeleteTripReturns404ForNonexistent(): void
    {
        $this->client->request('DELETE', '/api/trips/99999');
        $this->assertResponseStatusCodeSame(404);
    }
}
