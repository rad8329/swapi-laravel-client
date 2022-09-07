<?php

declare(strict_types=1);

namespace App\Pagination\SWApi;

use App\DTOs\SWApi\ResultSet;
use Illuminate\Pagination\LengthAwarePaginator;

class Paginator extends \Illuminate\Pagination\Paginator
{
    private const PER_PAGE = 10;

    public static function fromResponse(ResultSet $response): LengthAwarePaginator
    {
        return new LengthAwarePaginator($response->result, $response->count, self::PER_PAGE);
    }
}
