<?php

declare(strict_types=1);

namespace App\Http\Clients\SWApi;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class Client extends Http
{
    public static function get(string $url, array|string|null $query = null): Response
    {
        return static::timeout(config('swapi.timeout'))->get(config('swapi.url') . $url, $query);
    }
}
