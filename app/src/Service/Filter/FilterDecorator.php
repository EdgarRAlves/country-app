<?php

declare(strict_types=1);

namespace App\Service\Filter;

class FilterDecorator implements FilterInterface
{
    public function __construct(
        protected FilterInterface $filterInterface,
    ) {}

    public function filter(array $content): array
    {
        return $this->filterInterface->filter($content);
    }
}