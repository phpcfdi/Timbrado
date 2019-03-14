<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado;

class TimbrarResponse
{
    /** @var BinaryStatus */
    private $status;

    /** @var string */
    private $uuid;

    /** @var string */
    private $cfdi;

    /** @var string */
    private $errorMessage;

    /** @var array */
    private $rawData;

    public function __construct(
        BinaryStatus $status,
        string $uuid,
        string $cfdi,
        string $errorMessage,
        array $rawData = []
    ) {
        $this->status = $status;
        $this->uuid = $uuid;
        $this->cfdi = $cfdi;
        $this->errorMessage = $errorMessage;
        $this->rawData = $rawData;
    }

    public function status(): BinaryStatus
    {
        return $this->status;
    }

    public function uuid(): string
    {
        return $this->uuid;
    }

    public function cfdi(): string
    {
        return $this->cfdi;
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
