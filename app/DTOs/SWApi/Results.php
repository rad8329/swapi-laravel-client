<?php

declare(strict_types=1);

namespace App\DTOs\SWApi;

use Illuminate\Support\Collection;

/**
 * @template TKey of array-key
 * @template TResource
 *
 * @extends Collection<TKey, TResource>
 */
class Results extends Collection
{
}
