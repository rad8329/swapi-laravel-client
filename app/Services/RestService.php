<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RestService
{
    public function __construct(protected readonly Http $client)
    {
    }
}
