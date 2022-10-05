<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\Filter\FilterHandler;

use Exception;
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
        private $countriesUrl
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

    public function makeCallToGetCountries(): array
    {
        try {
            $response = $this->httpClient->request('GET', $this->countriesUrl . '/v2/all?fields=name,population,region');

            return $response->toArray();
        } catch (TransportExceptionInterface $e) {
            throw new Exception("Network error occurred.");
        } catch (ClientExceptionInterface $e) {
            throw new Exception("Client error caused the request to not be processed.");
        } catch (DecodingExceptionInterface $e) {
            throw new Exception("Error while trying to decode the response to an array.");
        } catch (RedirectionExceptionInterface $e) {
            throw new Exception("The number of maximum HTTP redirects messages was reached.");
        } catch (ServerExceptionInterface $e) {
            throw new Exception("The server is unable to fulfil the request.");
        }
    }

    public function getCachedCountries(): array
    {
        $countries_data = $this->cache->getItem('countries_data');

        if(!$countries_data->isHit()) {
             $countries_data->set($this->makeCallToGetCountries());

             $this->cache->save($countries_data);
        }

        return $countries_data->get();
    }
}
