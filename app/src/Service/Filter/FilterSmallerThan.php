<?php

declare(strict_types=1);

namespace App\Service\Filter;

class FilterSmallerThan extends FilterDecorator
{
    private string $filterSmallerThan;

    public function filter(array $content): array
    {
        $content = parent::filter($content);

        $countryNames = array_column($content, 'name');
        $countryKey = array_search($this->filterSmallerThan, $countryNames);
        $countryPopulation = $content[$countryKey]['population'];

        foreach ($content as $key => $country) {
            if ($country['population'] > $countryPopulation) {
                unset($content[$key]);
            }
        }

        return $content;
    }
}