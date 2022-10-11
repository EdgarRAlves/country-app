<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CountryControllerTest extends WebTestCase
{
    /**
    * @test
    */
    public function RouteCountriesShouldReturnSuccessfulResponse(): void
    {
        $client = static::createClient(); //DRY
        $client->request('GET', '/countries');

        $this->assertResponseIsSuccessful();
    }
}
