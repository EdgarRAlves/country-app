<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\Filter\FilterHandler;

use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
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
        private $countriesUrl,
        private LoggerInterface $logger
    ) {}

    public function getFilteredCountries(string $sort, string $direction, string $countryFilter, string $regionFilter): array
    {
        $countryArray = $this->getCachedCountries();

        $countryArray = $this->filterHandler->filter($countryArray, $countryFilter, $regionFilter);

        if (in_array($sort, ['population', 'region'])) {
            $countryArray = $this->sort->sort($countryArray, $sort, $direction);
        }

        return $countryArray;
    }

    public function getUnfilteredCountries(): array
    {
        return $this->getCachedCountries();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function makeCallToGetCountries(): array
    {
        try {
            $response = $this->httpClient->request('GET', $this->countriesUrl . '/v2/all?fields=name,population,region');

            return $response->toArray();
        } catch (Exception $e) {
            $this->logger->critical($e->getMessage(),[
                "Code: " . $e->getCode(),
                "Trace: " . $e->getTraceAsString()
            ]);
            return [];
        }
    }

    public function getCachedCountries(): array
    {
        $countries_data = $this->cache->getItem('countries_data');

        if(!$countries_data->isHit() || $countries_data->get() == []) {
             $countries_data->set($this->makeCallToGetCountries());

             $this->cache->save($countries_data);
        }

        return $countries_data->get();
    }
}
