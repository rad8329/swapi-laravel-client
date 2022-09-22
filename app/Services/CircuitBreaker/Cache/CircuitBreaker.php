<?php

declare(strict_types=1);

namespace App\Services\CircuitBreaker\Cache;

class CircuitBreaker
{
    public const MAXIMUM_OF_FAILURES = 5;

    /** @var int<0,max> */
    private int $numberOfFailures = 0;

    private bool $isOpen = true;

    /**
     * @param int<0, max> $maximumOfFailures
     */
    public function __construct(private readonly int $maximumOfFailures = self::MAXIMUM_OF_FAILURES)
    {
    }

    public function addFailure(): self
    {
        if (++$this->numberOfFailures === $this->maximumOfFailures) {
            $this->isOpen = false;
        }

        return $this;
    }

    public function isClosed(): bool
    {
        return !$this->isOpen;
    }
}
