<?php

declare(strict_types=1);

namespace App\Model;

use App\Exception\DomainException;

final class Money
{
    public readonly string $currency;
    public readonly float $value;

    public function __construct(float $value, string $currency)
    {
        if (0 > $value) {
            throw new DomainException('Money amount cannot be a negative number');
        }

        if (!preg_match('|^[a-z]{3}$|i', $currency)) {
            throw new DomainException('Currency must be compatible to ISO 3166-1 alpha-3 standard');
        }

        $this->value = $value;
        $this->currency = strtoupper($currency);
    }

    public function withValue(float $value): self
    {
        return new self($value, $this->currency);
    }
}
