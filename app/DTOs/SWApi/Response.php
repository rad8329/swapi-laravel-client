<?php

declare(strict_types=1);

namespace App\DTOs\SWApi;

/**
 * @template T
 */
class Response
{
    /**
     * @param Results<T> $result a SWApi resource collection
     */
    public function __construct(readonly Results $result, readonly int $count)
    {
    }
}
