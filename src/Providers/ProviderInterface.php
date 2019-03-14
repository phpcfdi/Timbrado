<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Providers;

use PhpCfdi\Timbrado\AcuseResponse;
use PhpCfdi\Timbrado\CancelarResponse;
use PhpCfdi\Timbrado\ObtenerResponse;
use PhpCfdi\Timbrado\TimbrarResponse;

interface ProviderInterface
{
    public function timbrar(string $xmlComprobante): TimbrarResponse;

    public function obtener(string $xmlComprobante): ObtenerResponse;

    public function cancelar(string $rfcEmisor, string $folioFiscal): CancelarResponse;

    public function acuse(string $rfcEmisor, string $folioFiscal): AcuseResponse;
}
