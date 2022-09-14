<?php

namespace App\Tests\Controller; // So ye :| what kind of thest this is Unit, Integration, Functional ? <- I know which one this is but, your structure of tests doesn't tell

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CountryControllerTest extends WebTestCase
{
    //Small tip
    /**
    * @test
    */
    public function testRouteCountriesIsWorking(): void // and then you gen get rid of 'test....' part on the name
    {
        $client = static::createClient(); //DRY
        $client->request('GET', '/countries');
        $client->getResponse()->getContent(); // Trash - this does nothing in current code

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'List of countries');
    }

    public function testRouteEuropeIsWorking(): void // also, recently i learned about this naming convention "{methodThatYouAreTesting}WHEN{whatKindOfSituationAreYouTesting}SHOULD{whatKindOfOutcomeYouExpect}", for ex. countriesWhenCallIsMadeToGetListOfCountriesInEuropeShould... well it's still not a good name, but that is expected since making a good name is also part of programming ;) - if test would faill, you should be abble to tell from failing test name, what kind of situation went bad ;)
    {
        $client = static::createClient();
        $client->request('GET', '/countries/Europe');
        $client->getResponse()->getContent(); // Trash - this does nothing in current code

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'List of countries in Europe');
    }

    public function testRouteSmallerThanLithuaniaIsWorking(): void
    {
        $client = static::createClient();
        $client->request('GET', '/countries/smallerThanLithuania');
        $client->getResponse()->getContent(); // Trash - this does nothing in current code

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'List of countries smaller in population than Lithuania');
    }
}
