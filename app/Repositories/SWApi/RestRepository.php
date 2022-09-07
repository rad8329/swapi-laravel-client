<?php

declare(strict_types=1);

namespace App\Repositories\SWApi;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Http;

class RestRepository extends \App\Repositories\RestRepository
{
    protected string $url;
    protected PendingRequest $request;

    public function __construct(Http $client, Cache $cache, Date $date)
    {
        $this->url = config('swapi.url');
        $this->request = $client::timeout(config('swapi.timeout'));

        parent::__construct($client, $cache, $date);
    }
}
