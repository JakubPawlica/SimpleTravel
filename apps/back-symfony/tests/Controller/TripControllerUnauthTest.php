<?php

namespace App\Tests\Controller;

use App\Tests\DatabaseTestCase;

class TripControllerUnauthTest extends DatabaseTestCase
{
    public function testCreateTripReturns401WithoutSession(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/trips', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'tripName' => 'Brak sesji',
            'destination' => 'Warszawa',
            'start_date' => '2025-06-01',
            'end_date' => '2025-06-10',
            'description' => 'Test bez zalogowanego użytkownika'
        ]));

        $this->assertSame(401, $client->getResponse()->getStatusCode());
    }
}
