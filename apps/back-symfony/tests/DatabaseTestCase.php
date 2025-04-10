<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class DatabaseTestCase extends WebTestCase
{
    protected function setUp(): void
    {
        static::ensureKernelShutdown();
        parent::setUp();
    }
}
