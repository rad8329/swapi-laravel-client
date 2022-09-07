<?php

declare(strict_types=1);

namespace App\DTOs\SWApi;

use Illuminate\Support\Collection;

class Response
{
    /**
     * @param Collection<int, Planet> $result
     */
    public function __construct(readonly Collection $result, readonly int $count)
    {
    }
}
