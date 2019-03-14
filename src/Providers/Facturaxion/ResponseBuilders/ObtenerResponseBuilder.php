<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Providers\Facturaxion\ResponseBuilders;

use PhpCfdi\Timbrado\BinaryStatus;
use PhpCfdi\Timbrado\ObtenerResponse;
use PhpCfdi\Timbrado\Utils\CfdiData;

class ObtenerResponseBuilder extends AbstractResponseBuilder
{
    public function getResultName(): string
    {
        return 'RecuperaCFDIObjetoResult';
    }

    public function status(): BinaryStatus
    {
        $rawStatus = boolval($this->get('EstatusRespuesta', '0'));
        return ($rawStatus) ? BinaryStatus::success() : BinaryStatus::failure();
    }

    public function uuid(): string
    {
        $uuid = $this->get('FolioFiscal');
        if ('' === $uuid) {
            $cfdi = $this->cfdi();
            if ('' !== $cfdi) {
                $uuid = CfdiData::createFromXml($cfdi)->uuid();
            }
        }
        return $uuid;
    }

    public function cfdi(): string
    {
        return $this->get('XMLCFDI');
    }

    public function create(): ObtenerResponse
    {
        return new ObtenerResponse(
            $this->status(),
            $this->uuid(),
            $this->cfdi(),
            $this->errorMessage(),
            $this->rawData
        );
    }
}
