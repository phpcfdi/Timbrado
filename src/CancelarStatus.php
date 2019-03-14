<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado;

final class CancelarStatus
{
    const SUCCESS = 'SUCCESS';

    const PENDING = 'PENDING';

    const FAILURE = 'FAILURE';

    /** @var string */
    private $value;

    public function __construct(string $value)
    {
        if (! in_array($value, [self::SUCCESS, self::PENDING, self::FAILURE], true)) {
            throw new \RuntimeException(
                sprintf('The status "%s" is not one of the defined valid constants', $value)
            );
        }
        $this->value = $value;
    }

    public static function success(): self
    {
        return new self(self::SUCCESS);
    }

    public static function pending(): self
    {
        return new self(self::PENDING);
    }

    public static function failure(): self
    {
        return new self(self::FAILURE);
    }

    public function isSuccess(): bool
    {
        return self::SUCCESS === $this->value;
    }

    public function isPending(): bool
    {
        return self::PENDING === $this->value;
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
        return $this->value;
    }
}
