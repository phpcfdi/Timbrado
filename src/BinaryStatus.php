<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado;

final class BinaryStatus
{
    const SUCCESS = true;

    const FAILURE = false;

    /** @var bool */
    private $value;

    public function __construct(bool $value)
    {
        $this->value = $value;
    }

    public static function success(): self
    {
        return new self(self::SUCCESS);
    }

    public static function failure(): self
    {
        return new self(self::FAILURE);
    }

    public function isSuccess(): bool
    {
        return self::SUCCESS === $this->value;
    }

    public function isFailure(): bool
    {
        return self::FAILURE === $this->value;
    }

    public function equalsTo(self $status): bool
    {
        return ($status->value === $this->value);
    }

    public function __toString(): string
    {
        return ($this->value) ? 'SUCCESS' : 'FAILURE';
    }
}
