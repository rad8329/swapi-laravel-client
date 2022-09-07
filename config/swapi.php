<?php

return [
    'url' => env('SWAPI_URL', 'https://swapi.dev/api/'),
    'cache_expiration' => env('SWAPI_CACHE_EXPIRATION_IN_SECONDS', 3600),
    'timeout' => env('SWAPI_TIMEOUT_IN_SECONDS', 1)
];
