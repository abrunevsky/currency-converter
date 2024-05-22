<?php

declare(strict_types=1);

namespace App\Model;

final class Money
{
    public readonly string $iso;

    public function __construct(
        public readonly float $value,
        string $iso,
    ) {
        $this->iso = strtoupper($iso);
    }

    public function withValue(float $value): self
    {
        return new self($value, $this->iso);
    }
}
