<?php

namespace App\Tests\Controller;

use App\Tests\DatabaseTestCase;

class AuthControllerUnauthTest extends DatabaseTestCase
{
    public function testMeReturns401WithoutSession(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/me');

        $this->assertResponseStatusCodeSame(401);
    }
}
