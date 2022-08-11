<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CountryController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    #[Route('/countries', name: 'countries')]
    public function index(): Response
    {
        $response = $this->client->request(
            'GET',
            'https://restcountries.com/v2/all?fields=name,population,region'
        );

        return $this->render(
            'country/index.html.twig',
            [
                'countries' => $response->toArray()
            ]
        );
    }

    #[Route('/countries/Europe', name: 'europe')]
    public function countriesFromEurope(): Response
    {
        $response = $this->client->request(
            'GET',
            'https://restcountries.com/v2/region/Europe?fields=name,population,region'
        );

        return $this->render(
            'country/europe.html.twig',
            [
                'countries' => $response->toArray()
            ]
        );
    }

    #[Route('/countries/smallerThanLithuania', name: 'smaller_lithuania')]
    public function countriesSmallerThanLithuania(): Response
    {
        $response = $this->client->request(
            'GET',
            'https://restcountries.com/v2/all?fields=name,population,region'
        );

        $countries = $response->toArray();
        $countryNames = array_column($countries, 'name');
        $lithuania = array_search('Lithuania', $countryNames);
        $lithuaniaPopulation = $countries[$lithuania]['population'];

        $result = [];

        foreach ($countries as $country) {
            if ($country['population'] < $lithuaniaPopulation) {
                $result[] = $country;
            }
        }

        return $this->render(
            'country/smaller_lithuania.html.twig',
            [
                'countries' => $result
            ]
        );
    }
}
