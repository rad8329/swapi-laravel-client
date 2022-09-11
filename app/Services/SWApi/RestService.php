<?php

declare(strict_types=1);

namespace App\Services\SWApi;

use App\Http\Clients\SWApi\Client;
use App\Services\Cache\CacheWithOptionsResolver;
use Closure;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Support\Facades\Date;

class RestService extends \App\Services\RestService
{
    public function __construct(Client $client,
                                protected readonly CacheRepository $cache,
                                protected readonly Date $date,
                                protected readonly CacheWithOptionsResolver $cacheResolver)
    {
        parent::__construct($client);
    }

    /**
     * Get an item from the cache, or execute the given Closure and store the result.
     */
    protected function remember(string $key, Closure $callable): mixed
    {
        return $this->cache->remember(
            $key,
            $this->date::now()->addSeconds(config('swapi.cache_expiration')),
            $callable
        );
    }
}
