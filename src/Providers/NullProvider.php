<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Providers;

use PhpCfdi\Timbrado\AcuseResponse;
use PhpCfdi\Timbrado\CancelarResponse;
use PhpCfdi\Timbrado\Exceptions\TimbradoRuntimeException;
use PhpCfdi\Timbrado\ObtenerResponse;
use PhpCfdi\Timbrado\TimbrarResponse;

class NullProvider implements ProviderInterface
{
    public function timbrar(string $xmlComprobante): TimbrarResponse
    {
        throw new TimbradoRuntimeException('This provider is not able to perform timbrar action');
    }

    public function obtener(string $xmlComprobante): ObtenerResponse
    {
        throw new TimbradoRuntimeException('This provider is not able to perform obtener action');
    }

    public function cancelar(string $rfcEmisor, string $folioFiscal): CancelarResponse
    {
        throw new TimbradoRuntimeException('This provider is not able to perform cancelar action');
    }

    public function acuse(string $rfcEmisor, string $folioFiscal): AcuseResponse
    {
        throw new TimbradoRuntimeException('This provider is not able to perform acuse action');
    }
}
