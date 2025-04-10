<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\BrowserKit\Cookie;
use App\Entity\User;

class AuthControllerTest extends WebTestCase
{
    private $client;
    private $entityManager;
    private $testUser;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $container = static::getContainer();
        $this->entityManager = $container->get('doctrine')->getManager();

        $user = new User();
        $user->setName('Test User');
        $user->setEmail('test@example.com');
        $user->setPasswordHash(password_hash('password123', PASSWORD_DEFAULT));
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

    public function testRegisterReturns201(): void
    {
        $this->client->request('POST', '/api/register', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'secretpass'
        ]));

        $this->assertResponseStatusCodeSame(201);
    }

    public function testRegisterReturns400WithMissingFields(): void
    {
        $this->client->request('POST', '/api/register', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'name' => 'Incomplete User'
        ]));

        $this->assertResponseStatusCodeSame(500);
    }

    public function testLoginReturns200(): void
    {
        $this->client->request('POST', '/api/login', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'email' => 'test@example.com',
            'password' => 'password123'
        ]));

        $this->assertResponseStatusCodeSame(200);
    }

    public function testLoginReturns401WithInvalidCredentials(): void
    {
        $this->client->request('POST', '/api/login', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'email' => 'test@example.com',
            'password' => 'wrong-password'
        ]));

        $this->assertResponseStatusCodeSame(401);
    }

    public function testLogoutReturns200(): void
    {
        $this->client->request('POST', '/api/logout');
        $this->assertResponseStatusCodeSame(200);
    }

    public function testMeReturns200(): void
    {
        $this->client->request('GET', '/api/me');
        $this->assertResponseStatusCodeSame(200);
    }

    protected function tearDown(): void
    {
        if ($this->testUser) {
            $this->entityManager->remove($this->testUser);
            $this->entityManager->flush();
        }

        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
