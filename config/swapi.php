<?php

return [
    'url' => env('SWAPI_URL', 'https://swapi.dev/api/'),
    'cache_expiration' => env('SWAPI_CACHE_EXPIRATION_IN_SECONDS', 3600),
    'timeout' => env('SWAPI_TIMEOUT_IN_SECONDS', 3),
    'circuit_beaker' => [
        'threshold' => env('SWAPI_CIRCUIT_BREAKER_THRESHOLD', 3),
        'time_to_live_in_seconds' => env('SWAPI_TIME_TO_LIVE_IN_SECONDS', 60),
    ]
];
