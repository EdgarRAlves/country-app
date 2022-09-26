<?php

declare(strict_types=1);

namespace App\Service;

class FilterRegion
{
    public function filterRegion(array $content, string $region): array
    {
        foreach ($content as $key => $country) {
            if ($country['region'] != $region) {
                unset($content[$key]);
            }
        }

        return $content;
    }
}