<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado;

class AcuseResponse
{
    /** @var BinaryStatus */
    private $status;

    /** @var string */
    private $acuse;

    /** @var string */
    private $errorMessage;

    /** @var array */
    private $rawData;

    public function __construct(BinaryStatus $status, string $acuse, string $errorMessage, array $rawData = [])
    {
        $this->status = $status;
        $this->acuse = $acuse;
        $this->errorMessage = $errorMessage;
        $this->rawData = $rawData;
    }

    public function status(): BinaryStatus
    {
        return $this->status;
    }

    public function acuse(): string
    {
        return $this->acuse;
    }

    public function errorMessage(): string
    {
        return $this->errorMessage;
    }

    public function rawData(): array
    {
        return $this->rawData;
    }
}
