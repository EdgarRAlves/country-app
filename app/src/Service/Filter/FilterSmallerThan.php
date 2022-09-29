<?php

declare(strict_types=1);

namespace App\Service\Filter;

class FilterSmallerThan implements FilterInterface
{
    public function filter(array $content, string $filter): array
    {
        $countryNames = array_column($content, 'name');
        $countryKey = array_search($filter, $countryNames);
        $countryPopulation = $content[$countryKey]['population'];

        foreach ($content as $key => $country) {
            if ($country['population'] > $countryPopulation) {
                unset($content[$key]);
            }
        }

        return $content;
    }
}