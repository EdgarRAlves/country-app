<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\Filter\FilterHandler;

use Exception;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Country
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private Sort $sort,
        private FilterHandler $filterHandler,
        private CacheInterface $cache,
        private $countriesUrl
    ) {}

    public function getFilteredCountries(string $sort, string $direction, string $countryFilter, string $regionFilter): array
    {
        $countryArray = $this->makeCallToGetCountries();

        $countryArray = $this->filterHandler->filter($countryArray, $countryFilter, $regionFilter);

        if (in_array($sort, ['population', 'region'])) {
            $countryArray = $this->sort->sort($countryArray, $sort, $direction);
        }

        return $countryArray;
    }

    public function getUnfilteredCountries(): array
    {
        return $this->makeCallToGetCountries();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function makeCallToGetCountries(): array
    {
        $countries = $this->cache->get('countries_data', function() {
            $response = $this->httpClient->request('GET', $this->countriesUrl . '/v2/all?fields=name,population,region');

            if (200 !== $response->getStatusCode()) {
                throw new Exception('The call to get countries was not successful.');
            }

            return $response->toArray();
        });

        return $countries;
    }
}
