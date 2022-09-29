<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\Filter\FilterConcreteComponent;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Country
{
    public function __construct(
        private HttpClientInterface     $httpClient,
        private Sort                    $sort,
        private FilterConcreteComponent $filterHandler,
        private                         $countriesUrl
    ) {}

    public function getFilteredCountries(string $sort, string $direction): array
    {
        $countryArray = $this->makeCallToGetCountries();

        $countryArray = $this->filterHandler->filter($countryArray);

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
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function makeCallToGetCountries(): array
    {
        $response = $this->httpClient->request('GET', $this->countriesUrl . '/v2/all?fields=name,population,region', [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        if (200 !== $response->getStatusCode()) {
            throw new Exception('The call to get countries was not successful.');
        }

        $responseJson = $response->getContent();

        return json_decode($responseJson, true);
    }
}
