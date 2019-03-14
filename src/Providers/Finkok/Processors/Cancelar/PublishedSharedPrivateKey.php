<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Providers\Finkok\Processors\Cancelar;

class PublishedSharedPrivateKey extends AbstractCancelarUsingCancel implements CancelarProcessorInterface
{
    public function cancel(string $rfcEmisor, string $folioFiscal): array
    {
        return $this->callSoapCancel('sign_cancel', [
            'UUIDS' => ['uuids' => [$folioFiscal]],
            'taxpayer_id' => $rfcEmisor,
        ]);
    }
}
