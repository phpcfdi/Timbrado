<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Providers\Facturaxion\ResponseBuilders;

use PhpCfdi\Timbrado\BinaryStatus;
use PhpCfdi\Timbrado\TimbrarResponse;

class TimbrarResponseBuilder extends AbstractResponseBuilder
{
    public function getResultName(): string
    {
        return 'TimbrarObjetoResult';
    }

    public function status(): BinaryStatus
    {
        $rawStatus = boolval($this->get('EstatusRespuesta', '0'));
        return ($rawStatus) ? BinaryStatus::success() : BinaryStatus::failure();
    }

    public function uuid(): string
    {
        return $this->get('FolioFiscal');
    }

    public function cfdi(): string
    {
        return $this->get('XMLTimbrado');
    }

    public function create(): TimbrarResponse
    {
        return new TimbrarResponse(
            $this->status(),
            $this->uuid(),
            $this->cfdi(),
            $this->errorMessage(),
            $this->rawData
        );
    }
}
