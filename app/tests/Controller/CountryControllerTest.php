<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CountryControllerTest extends WebTestCase
{
    public function testRouteCountriesIsWorking(): void
    {
        $client = static::createClient();
        $client->request('GET', '/countries');
        $client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'List of countries');
    }

    public function testRouteEuropeIsWorking(): void
    {
        $client = static::createClient();
        $client->request('GET', '/countries/Europe');
        $client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'List of countries in Europe');
    }

    public function testRouteSmallerThanLithuaniaIsWorking(): void
    {
        $client = static::createClient();
        $client->request('GET', '/countries/smallerThanLithuania');
        $client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'List of countries smaller in population than Lithuania');
    }
}
