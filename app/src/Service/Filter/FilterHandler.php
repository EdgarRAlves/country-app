<?php

declare(strict_types=1);

namespace App\Service\Filter;

class FilterHandler
{
    public function __construct(
        private FilterRegion $filterRegion,
        private FilterSmallerThan $filterSmallerThan
    ) {}

    public function filter(array $content, string $countryFilter, string $regionFilter): array
    {
        if (!empty($countryFilter)) {
            $content = $this->filterSmallerThan->filter($content, $countryFilter);
        }

        if (!empty($regionFilter)) {
            $content = $this->filterRegion->filter($content, $regionFilter);
        }

        return $content;
    }
}