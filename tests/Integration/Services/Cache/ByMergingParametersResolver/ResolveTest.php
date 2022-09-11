<?php

declare(strict_types=1);

namespace Tests\Integration\Services\Cache\ByMergingParametersResolver;

use App\Services\Cache\CacheWithOptionsResolver;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @covers \App\Services\Cache\ByMergingParametersResolver::resolve
 */
class ResolveTest extends TestCase
{
    use WithFaker;

    /**
     * Test that SUT class is being bound by the service container.
     */
    public function test_it_is_properly_bound(): void
    {
        $resolver = app(CacheWithOptionsResolver::class);

        $this->assertInstanceOf(CacheWithOptionsResolver::class, $resolver);
    }

    public function test_it_meets_idempotency(): void
    {
        $resolver = app(CacheWithOptionsResolver::class);

        $term = $this->faker->words(2);

        $hash1 = $resolver->resolve('a_method_to_be_cached', ['search' => $term]);

        $hash2 = $resolver->resolve('a_method_to_be_cached', ['search' => $term]);

        $this->assertSame($hash1, $hash2);
    }
}
