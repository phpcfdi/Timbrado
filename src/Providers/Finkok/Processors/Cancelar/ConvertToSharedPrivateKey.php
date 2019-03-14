<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Providers\Finkok\Processors\Cancelar;

use CfdiUtils\OpenSSL\OpenSSL;
use PhpCfdi\Timbrado\Providers\Finkok\FinkokSettings;
use PhpCfdi\Timbrado\Soap\SoapClientInterface;

class ConvertToSharedPrivateKey extends AbstractCancelarUsingCancel implements CancelarProcessorInterface
{
    /** @var OpenSSL */
    private $openSsl;

    public function __construct(SoapClientInterface $soapClient, FinkokSettings $settings, OpenSSL $openSsl)
    {
        parent::__construct($soapClient, $settings);
        $this->openSsl = $openSsl;
    }

    public function cancel(string $rfcEmisor, string $folioFiscal): array
    {
        $settings = $this->getSettings();

        return $this->callSoapCancel('cancel', [
            'UUIDS' => ['uuids' => [$folioFiscal]],
            'taxpayer_id' => $rfcEmisor,
            'cer' => $settings->certificate(),
            'key' => $this->doConvertPemPrivateKey($settings),
        ]);
    }

    public function doConvertPemPrivateKey(FinkokSettings $settings): string
    {
        return $this->openSsl->pemKeyProtectInOut(
            $settings->privateKey(),
            $settings->passPhrase(),
            $settings->password()
        );
    }
}
