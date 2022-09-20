<?php

declare(strict_types=1);

namespace App\Service;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CountryRepository
{
    public function __construct(
        private HttpClientInterface $client,
        private PaginatorInterface $paginator
    ) {}

    public function getCountries(string $sort, string $direction, string $filter): array // Think more about SOLID, KISS and this function - is this function doesn one thing or much more ?
    {
        if ($filter == 'europe') {
            $countryArray = $this->getCountriesEurope();
        } elseif ($filter == 'smallerthanlithuania') {
            $countryArray = $this->getAllCountries();

            $countryNames = array_column($countryArray, 'name');
            $lithuania = array_search('Lithuania', $countryNames); // Hardkode never makes code beautifull
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

    public function getCountriesEurope(): array // I would suggest to think more about naming convention - Look at what actualy does this function and what it names is ? maybe there is better name fot it ? I have some suggestion in mind but will not tell you right now :)
    {
        $response = $this->client->request(
            'GET',
            'https://restcountries.com/v2/region/Europe?fields=name,population,region' // Think what would happen if url, pass/token would change on the other side - what you would need to do in order to fix it without doeing any changes to the code ?
        );

        return $response->toArray(); // Also - good code is that could handle unexpected situations - what would happen if client o other obect in the chanin would throw exception ? And on this line - can you guaranty that $response alwyas return array ? maybe there wouldn't be any kind of response or response style would change ?
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
