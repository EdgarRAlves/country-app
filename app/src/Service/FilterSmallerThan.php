<?php

declare(strict_types=1);

namespace App\Service;

class FilterSmallerThan
{
    public function filterSmallerThanByPopulation(array $content, string $country): array
    {
        $countryNames = array_column($content, 'name');
        $countryKey = array_search($country, $countryNames);
        $countryPopulation = $content[$countryKey]['population'];

        foreach ($content as $key => $country) {
            if ($country['population'] > $countryPopulation) {
                unset($content[$key]);
            }
        }

        return $content;
    }
}