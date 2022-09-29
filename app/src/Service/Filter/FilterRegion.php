<?php

declare(strict_types=1);

namespace App\Service\Filter;

class FilterRegion extends FilterDecorator
{
    public function filter(array $content): array
    {
        $content = parent::filter($content);

        foreach ($content as $key => $country) {
            if ($country['region'] != $this->filterRegion) {
                unset($content[$key]);
            }
        }

        return $content;
    }
}