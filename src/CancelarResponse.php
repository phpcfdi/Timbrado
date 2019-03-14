<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado;

class CancelarResponse
{
    const STATUS_SUCCESS = 'SUCCESS';

    const STATUS_PENDING = 'PENDING';

    const STATUS_FAILURE = 'FAILURE';

    /** @var CancelarStatus */
    private $status;

    /** @var string */
    private $errorMessage;

    /** @var array */
    private $rawData;

    public function __construct(CancelarStatus $status, string $errorMessage, array $rawData = [])
    {
        $this->status = $status;
        $this->errorMessage = $errorMessage;
        $this->rawData = $rawData;
    }

    public function status(): CancelarStatus
    {
        return $this->status;
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
