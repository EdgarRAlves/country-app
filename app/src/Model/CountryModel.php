<?php

namespace App\Model;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CountryModel
{
    private HttpClientInterface $client;
    private PaginatorInterface $paginator;

    public function __construct(HttpClientInterface $client, PaginatorInterface $paginator)
    {
        $this->client = $client;
        $this->paginator = $paginator;
    }

    public function getCountries(string $sort, string $direction, string $filter): array
    {
        if ($filter == 'europe') {
            $countryArray = $this->getCountriesEurope();
        } elseif ($filter == 'smallerthanlithuania') {
            $countryArray = $this->getAllCountries();

            $countryNames = array_column($countryArray, 'name');
            $lithuania = array_search('Lithuania', $countryNames);
            $lithuaniaPopulation = $countryArray[$lithuania]['population'];

            foreach ($countryArray as $key => $country) {
                if ($country['population'] > $lithuaniaPopulation) {
                    unset($countryArray[$key]);
                }
            }
        } elseif (empty($filter)) {
            $countryArray = $this->getAllCountries();
        }

        if (in_array($sort, ['population', 'region'])) {
            $countryArray = $this->sort($countryArray, $sort, $direction);
        }

        return $countryArray;
    }

    public function getAllCountries(): array
    {
        $response = $this->client->request(
            'GET',
            'https://restcountries.com/v2/all?fields=name,population,region'
        );

        return $response->toArray();
    }

    public function getCountriesEurope(): array
    {
        $response = $this->client->request(
            'GET',
            'https://restcountries.com/v2/region/Europe?fields=name,population,region'
        );

        return $response->toArray();
    }

    public function sort(array $content, string $key, string $direction): array
    {
        if ($direction == 'desc') {
            usort($content, function ($a, $b) use ($key) {
                return $b[$key] <=> $a[$key];
            });
        } elseif ($direction == 'asc') {
            usort($content, function ($a, $b) use ($key) {
                return $a[$key] <=> $b[$key];
            });
        }

        return $content;
    }

    public function paginate(array $content, int $page, int $limit)
    {
        return $this->paginator->paginate(
            $content,
            $page,
            $limit
        );
    }
}
