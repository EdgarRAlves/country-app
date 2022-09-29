<?php

declare(strict_types=1);

namespace App\Service\Filter;

interface FilterInterface
{
    public function filter(array $content): array;
}