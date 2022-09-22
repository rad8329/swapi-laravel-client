<?php

declare(strict_types=1);

namespace App\Services\CircuitBreaker\Cache;

class CircuitBreaker
{
    public const MAXIMUM_OF_FAILURES = 5;

    private int $numberOfFailures = 0;

    private bool $isOpen = true;

    public function __construct(private readonly int $threshold = self::MAXIMUM_OF_FAILURES)
    {
    }

    public function addFailure(): self
    {
        if (++$this->numberOfFailures === $this->threshold) {
            $this->isOpen = false;
        }

        return $this;
    }

    public function isClosed(): bool
    {
        return !$this->isOpen;
    }
}
