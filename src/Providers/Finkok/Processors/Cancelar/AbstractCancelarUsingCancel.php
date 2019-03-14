<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Providers\Finkok\Processors\Cancelar;

use PhpCfdi\Timbrado\Providers\Finkok\FinkokSettings;
use PhpCfdi\Timbrado\Soap\SoapClientInterface;

abstract class AbstractCancelarUsingCancel implements CancelarProcessorInterface
{
    /** @var SoapClientInterface */
    private $soapClient;

    /** @var FinkokSettings */
    private $settings;

    public function __construct(SoapClientInterface $soapClient, FinkokSettings $settings)
    {
        $this->soapClient = $soapClient;
        $this->settings = $settings;
    }

    public function getSoapClient(): SoapClientInterface
    {
        return $this->soapClient;
    }

    public function getSettings(): FinkokSettings
    {
        return $this->settings;
    }

    protected function callSoapCancel(string $method, array $soapParameters): array
    {
        $settings = $this->getSettings();
        $soapClient = $this->getSoapClient();

        return $soapClient->execute($method, [array_merge($soapParameters, [
            'username' => $settings->username(),
            'password' => $settings->password(),
            // Indica al ws si se va a guarda o no en el pending buffer.
            // Si ocurre un error al contactar al SAT
            'store_pending' => false,
        ])]);
    }
}
