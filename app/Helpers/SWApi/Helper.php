<?php

declare(strict_types=1);

namespace App\Helpers\SWApi;

use Illuminate\Support\Str;

class Helper
{
    public static function extractPlanetIdFromUrl(string $url): int
    {
        return self::extractIdFromUrl($url, 'planets');
    }

    public static function extractPeopleIdFromUrl(string $url): int
    {
        return self::extractIdFromUrl($url, 'people');
    }

    private static function extractIdFromUrl(string $url, string $pattern): int
    {
        return (int) Str::of($url)->remove(sprintf('%s%s/', config('swapi.url'), $pattern))->toString();
    }
}
