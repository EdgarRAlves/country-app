<?php

namespace App\Model;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CountryModel
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getCountries(string $sort, string $direction, array $filter) {
        if (in_array('all', $filter)) {
            $countryArray = $this->getAllCountries();
        } elseif (in_array('europe', $filter)) {
            $countryArray = $this->getCountriesEurope();
        }

        if (in_array('smallerThanLithuania', $filter)) {
            $countryNames = array_column($countryArray, 'name');
            $lithuania = array_search('Lithuania', $countryNames);
            $lithuaniaPopulation = $countryArray[$lithuania]['population'];

            foreach ($countryArray as $key => $country) {
                if ($country['population'] > $lithuaniaPopulation) {
                    unset($countryArray[$key]);
                }
            }
        }

        if (in_array($sort, ['population', 'region'])) {
            $countryArray = $this->sort($countryArray, $sort, $direction);
        }

        return $countryArray;
    }

    public function getAllCountries() {
        $response = $this->client->request(
            'GET',
            'https://restcountries.com/v2/all?fields=name,population,region'
        );

        return $response->toArray();
    }

    public function getCountriesEurope() {
        $response = $this->client->request(
            'GET',
            'https://restcountries.com/v2/region/Europe?fields=name,population,region'
        );

        return $response->toArray();
    }

    public function sort ($content, $key, $direction) {
        if ($direction == 'desc') {
            usort($content, function ($a, $b) use($key){
                return $b[$key] <=> $a[$key];
            });
        } elseif ($direction == 'asc') {
            usort($content, function ($a, $b) use($key){
                return $a[$key] <=> $b[$key];
            });
        }

        return $content;
    }
}