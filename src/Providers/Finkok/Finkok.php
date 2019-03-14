<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Providers\Finkok;

use PhpCfdi\Timbrado\AcuseResponse;
use PhpCfdi\Timbrado\CancelarResponse;
use PhpCfdi\Timbrado\ObtenerResponse;
use PhpCfdi\Timbrado\Providers\ProviderInterface;
use PhpCfdi\Timbrado\TimbrarResponse;

class Finkok implements ProviderInterface
{
    /** @var FinkokFactory */
    private $factory;

    public function __construct(FinkokFactory $factory)
    {
        $this->factory = $factory;
    }

    public function getFactory(): FinkokFactory
    {
        return $this->factory;
    }

    public function timbrar(string $xmlComprobante): TimbrarResponse
    {
        $factory = $this->getFactory();
        $soap = $factory->createSoapClientForTimbrar();
        $settings = $factory->getCurrentSettings();

        $rawResponse = $soap->execute('stamp', [[
            'xml' => $xmlComprobante,
            'username' => $settings->username(),
            'password' => $settings->password(),
        ]]);

        return (new ResponseBuilders\TimbrarResponseBuilder($rawResponse))->create();
    }

    public function obtener(string $xmlComprobante): ObtenerResponse
    {
        $factory = $this->getFactory();
        $soap = $factory->createSoapClientForObtener();
        $settings = $factory->getCurrentSettings();

        $rawResponse = $soap->execute('Stamped', [[
            'xml' => $xmlComprobante,
            'username' => $settings->username(),
            'password' => $settings->password(),
        ]]);

        return (new ResponseBuilders\ObtenerResponseBuilder($rawResponse))->create();
    }

    public function cancelar(string $rfcEmisor, string $folioFiscal): CancelarResponse
    {
        $factory = $this->getFactory();

        $processor = $factory->createCancelarProcessor();
        $rawResponse = $processor->cancel($rfcEmisor, $folioFiscal);

        return (new ResponseBuilders\CancelarResponseBuilder($rawResponse))->create();
    }

    public function acuse(string $rfcEmisor, string $folioFiscal): AcuseResponse
    {
        $factory = $this->getFactory();
        $soap = $factory->createSoapClientForCancelar();
        $settings = $factory->getCurrentSettings();

        $rawResponse = $soap->execute('get_receipt', [[
            'uuid' => $folioFiscal,
            'taxpayer_id' => $rfcEmisor,
            'username' => $settings->username(),
            'password' => $settings->password(),
            // type: "R" para recepción o "C" para cancelación (???)
            'type' => 'C',
        ]]);

        return (new ResponseBuilders\AcuseResponseBuilder($rawResponse))->create();
    }
}
