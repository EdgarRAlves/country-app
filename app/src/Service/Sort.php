<?php

declare(strict_types=1);

namespace App\Service;

class Sort
{
    public function sort(array $content, string $key, string $direction): array
    {
        if ($direction == 'desc') {
            usort($content, function ($a, $b) use ($key) {
                return $b[$key] <=> $a[$key];
            });
        } elseif ($direction == 'asc') {
            usort($content, function ($a, $b) use ($key) {
                return $a[$key] <=> $b[$key];
            });
        }

        return $content;
    }
}