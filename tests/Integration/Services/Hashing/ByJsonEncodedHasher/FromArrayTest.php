<?php

namespace Tests\Integration\Services\Hashing\ByJsonEncodedHasher;

use App\Services\Hashing\ByJsonEncodedHasher;
use App\Services\Hashing\Hasher;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FromArrayTest extends TestCase
{
    use WithFaker;

    /**
     * Test that SUT class is being bound by the service container.
     */
    public function test_it_is_properly_bound(): void
    {
        $hasher = app(Hasher::class);

        $this->assertInstanceOf(ByJsonEncodedHasher::class, $hasher);
    }

    public function test_it_meets_idempotency(): void
    {
        $hasher = app(Hasher::class);

        $term = $this->faker->words(2);

        $hash1 = $hasher->fromArray(['search' => $term, '_cacheKey' => 'a_method_to_be_cached']);

        $hash2 = $hasher->fromArray(['search' => $term, '_cacheKey' => 'a_method_to_be_cached']);

        $this->assertSame($hash1, $hash2);
    }
}
