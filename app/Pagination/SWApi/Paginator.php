<?php

declare(strict_types=1);

namespace App\Pagination\SWApi;

use App\DTOs\SWApi\Response;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @template TResource
 */
class Paginator extends \Illuminate\Pagination\Paginator
{
    private const PER_PAGE = 10;

    /**
     * @param Response<TResource> $response
     */
    public static function fromResponse(Response $response): LengthAwarePaginator
    {
        return new LengthAwarePaginator($response->results, $response->count, self::PER_PAGE);
    }
}
