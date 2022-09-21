<?php

declare(strict_types=1);

namespace App\Trait;

use Knp\Component\Pager\Pagination\PaginationInterface;

trait PaginateTrait
{
    public function paginate(array $content, int $page, int $limit): PaginationInterface
    {
        return $this->paginator->paginate(
            $content,
            $page,
            $limit
        );
    }
}
