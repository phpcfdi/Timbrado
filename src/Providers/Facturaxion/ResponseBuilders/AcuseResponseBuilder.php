<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Providers\Facturaxion\ResponseBuilders;

use PhpCfdi\Timbrado\AcuseResponse;
use PhpCfdi\Timbrado\BinaryStatus;

class AcuseResponseBuilder extends AbstractResponseBuilder
{
    public function getResultName(): string
    {
        return 'RecuperaAcuseCancelacionResult';
    }

    public function status(): BinaryStatus
    {
        $rawStatus = boolval($this->get('EstatusRespuesta', '0'));
        return ($rawStatus) ? BinaryStatus::success() : BinaryStatus::failure();
    }

    public function acuse(): string
    {
        return $this->get('AcuseCancelacion');
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
