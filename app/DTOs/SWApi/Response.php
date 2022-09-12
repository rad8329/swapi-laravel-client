<?php

declare(strict_types=1);

namespace App\DTOs\SWApi;

/**
 * @template TResource
 */
class Response
{
    /**
     * @param Results<TResource> $results a SWApi resource collection
     */
    public function __construct(readonly Results $results, readonly int $count)
    {
    }
}
