<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthControllerUnauthenticatedTest extends WebTestCase
{
    public function testMeReturns401WithoutSession(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/me');

        $this->assertResponseStatusCodeSame(401);
    }
}
