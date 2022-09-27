<?php

declare(strict_types=1);

namespace App\Service;

class FilterRegion implements FilterInterface
{
    public function filter(array $content, string $filter): array
    {
        foreach ($content as $key => $country) {
            if ($country['region'] != $filter) {
                unset($content[$key]);
            }
        }

        return $content;
    }
}