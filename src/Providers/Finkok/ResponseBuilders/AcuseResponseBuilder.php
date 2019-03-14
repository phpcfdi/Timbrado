<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Providers\Finkok\ResponseBuilders;

use PhpCfdi\Timbrado\AcuseResponse;
use PhpCfdi\Timbrado\BinaryStatus;

class AcuseResponseBuilder extends AbstractResponseBuilder
{
    public function getResultName(): string
    {
        return 'get_receiptResult';
    }

    public function status(): BinaryStatus
    {
        $rawStatus = ($this->get('success', '0'));
        return ($rawStatus) ? BinaryStatus::success() : BinaryStatus::failure();
    }

    public function acuse(): string
    {
        return $this->get('receipt');
    }

    public function errorMessage(): string
    {
        return $this->get('error');
    }

    public function create(): AcuseResponse
    {
        return new AcuseResponse(
            $this->status(),
            $this->acuse(),
            $this->errorMessage(),
            $this->rawData
        );
    }
}
