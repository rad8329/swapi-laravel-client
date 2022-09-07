<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Http;

class RestRepository
{
    use Cacheable;

    public function __construct(readonly Http $client,
                                readonly Cache $cache,
                                readonly Date $date)
    {
    }
}
