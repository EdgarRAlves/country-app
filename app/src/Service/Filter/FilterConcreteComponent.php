<?php

declare(strict_types=1);

namespace App\Service\Filter;

class FilterConcreteComponent implements FilterInterface
{
    public function filter(array $content): array
    {
        return $content;
    }
}