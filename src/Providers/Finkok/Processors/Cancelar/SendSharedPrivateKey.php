<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Providers\Finkok\Processors\Cancelar;

class SendSharedPrivateKey extends AbstractCancelarUsingCancel implements CancelarProcessorInterface
{
    public function cancel(string $rfcEmisor, string $folioFiscal): array
    {
        $settings = $this->getSettings();

        return $this->callSoapCancel('cancel', [
            'UUIDS' => ['uuids' => [$folioFiscal]],
            'taxpayer_id' => $rfcEmisor,
            'cer' => trim($settings->certificate()),
            'key' => trim($settings->privateKey()),
        ]);
    }
}
