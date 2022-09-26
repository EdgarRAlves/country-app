<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class Country
{
    public function __construct(
        private HttpClientInterface $client,
        private Sort $sort,
        private FilterSmallerThan $filterSmallerThan,
        private FilterRegion $filterRegion
    ) {}

    public function getFilteredCountries(string $sort, string $direction, string $countryFilter, string $regionFilter): array
    {
        $countryArray = $this->makeCallToRestCountries();

        if (!empty($countryFilter)) {
            $countryArray = $this->filterSmallerThan->filterSmallerThanByPopulation($countryArray, $countryFilter);
        }

        if (!empty($regionFilter)) {
            $countryArray = $this->filterRegion->filterRegion($countryArray, $regionFilter);
        }

        if (in_array($sort, ['population', 'region'])) {
            $countryArray = $this->sort->sort($countryArray, $sort, $direction);
        }

        return $countryArray;
    }

    public function getUnfilteredCountries(): array
    {
        return $this->makeCallToRestCountries();
    }

    public function makeCallToRestCountries(): array
    {
        $response = $this->client->request(
            'GET',
            'https://restcountries.com/v2/all?fields=name,population,region' // Think what would happen if url, pass/token would change on the other side - what you would need to do in order to fix it without doeing any changes to the code ?
        );

        return $response->toArray(); // Also - good code is that could handle unexpected situations - what would happen if client o other obect in the chanin would throw exception ? And on this line - can you guaranty that $response alwyas return array ? maybe there wouldn't be any kind of response or response style would change ?
    }
}
