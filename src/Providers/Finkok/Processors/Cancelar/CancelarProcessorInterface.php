<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Providers\Finkok\Processors\Cancelar;

interface CancelarProcessorInterface
{
    public function cancel(string $rfcEmisor, string $folioFiscal): array;
}
