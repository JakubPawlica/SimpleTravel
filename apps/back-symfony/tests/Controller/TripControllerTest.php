<?php

namespace App\Tests\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\User;

class TripControllerTest extends WebTestCase
{
    private $entityManager;
    private $client;
    private $testUser;

    protected function setUp(): void
    {
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

    protected function tearDown(): void
    {
        if ($this->testUser) {
            $trips = $this->entityManager
                ->getRepository(\App\Entity\Trip::class)
                ->findBy(['createdBy' => $this->testUser]);

            foreach ($trips as $trip) {
                $this->entityManager->remove($trip);
            }

            $this->entityManager->remove($this->testUser);
            $this->entityManager->flush();
        }

        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }


}
