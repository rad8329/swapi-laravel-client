<?php

declare(strict_types=1);

namespace App\Pagination\SWApi;

use App\DTOs\SWApi\Response;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @template T
 */
class Paginator extends \Illuminate\Pagination\Paginator
{
    private const PER_PAGE = 10;

    /**
     * @param Response<T> $response
     */
    public static function fromResponse(Response $response): LengthAwarePaginator
    {
        return new LengthAwarePaginator($response->result, $response->count, self::PER_PAGE);
    }
}
