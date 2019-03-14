<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Providers\Facturaxion;

use PhpCfdi\Timbrado\AcuseResponse;
use PhpCfdi\Timbrado\CancelarResponse;
use PhpCfdi\Timbrado\ObtenerResponse;
use PhpCfdi\Timbrado\Providers\ProviderInterface;
use PhpCfdi\Timbrado\Soap\PhpSoapClient;
use PhpCfdi\Timbrado\Soap\SoapClientInterface;
use PhpCfdi\Timbrado\TimbrarResponse;
use Webmozart\Assert\Assert;

class Facturaxion implements ProviderInterface
{
    const WS_PRODUCTION = 'https://wstimbrado.facturaxion.com/WSTimbrado.svc?singleWsdl';

    const WS_DEVELOPMENT = 'https://wstimbradopruebas.facturaxion.com/WSTimbrado.svc?singleWsdl';

    /** @var FacturaxionSettings */
    private $settings;

    /** @var \PhpCfdi\Timbrado\Soap\SoapClientInterface */
    private $soapClient;

    public function __construct(FacturaxionSettings $settings, SoapClientInterface $soapClient = null)
    {
        $this->settings = $settings;
        $this->soapClient = $soapClient ?: $this->createDefaultSoapClient(self::WS_PRODUCTION);
    }

    public static function createDefaultSoapClient(string $endPoint): SoapClientInterface
    {
        Assert::notEmpty('Must set a non empty endpoint');
        $soapOptions = [
            'soap_version' => SOAP_1_1,
            'cache_wsdl' => WSDL_CACHE_BOTH,
            'exceptions' => true, // whether soap errors throw exceptions of type SoapFault
            'stream_context' => stream_context_create(['ssl' => ['verify_peer' => true]]),
            'keep_alive' => false, // whether to send the Connection: Keep-Alive header or Connection: close
            'trace' => true,
        ];
        return PhpSoapClient::createWithOptions($endPoint, $soapOptions, true);
    }

    public function getSettings(): FacturaxionSettings
    {
        return $this->settings;
    }

    public function getSoapClient(): SoapClientInterface
    {
        return $this->soapClient;
    }

    public function timbrar(string $xmlComprobante): TimbrarResponse
    {
        Assert::notEmpty($xmlComprobante, 'Timbrar method receive an empty xml');

        $wsResponse = $this->soapCall('TimbrarObjeto', [
            'XMLPreCFDI' => $xmlComprobante,
        ], 'parametrosEntradaWSTimbrado');

        return (new ResponseBuilders\TimbrarResponseBuilder($wsResponse))->create();
    }

    public function obtener(string $xmlComprobante): ObtenerResponse
    {
        Assert::notEmpty($xmlComprobante, 'Obtener method receive an empty xml');

        $wsResponse = $this->soapCall('RecuperaCFDIObjeto', [
            'XMLPreCFDI' => $xmlComprobante,
        ], 'parametrosEntradaRecuperacionCFDI');

        return (new ResponseBuilders\ObtenerResponseBuilder($wsResponse))->create();
    }

    public function cancelar(string $rfcEmisor, string $folioFiscal): CancelarResponse
    {
        Assert::notEmpty($rfcEmisor, 'Cancelar method receive an empty Rfc');
        Assert::notEmpty($folioFiscal, 'Cancelar method receive an empty UUID');

        $wsResponse = $this->soapCall('CancelarObjeto', [
            'RFCEmisor' => $rfcEmisor,
            'FolioFiscal' => $folioFiscal,
        ], 'parametrosEntradaWSCancelacion');

        return (new ResponseBuilders\CancelarResponseBuilder($wsResponse))->create();
    }

    public function acuse(string $rfcEmisor, string $folioFiscal): AcuseResponse
    {
        Assert::notEmpty($rfcEmisor, 'Acuse method receive an empty Rfc');
        Assert::notEmpty($folioFiscal, 'Acuse method receive an empty UUID');

        $wsResponse = $this->soapCall('RecuperaAcuseCancelacion', [
            'RFCEmisor' => $rfcEmisor,
            'FolioFiscal' => $folioFiscal,
        ], 'parametrosEntradaRecuperacionAcuseCancelacion');

        return (new ResponseBuilders\AcuseResponseBuilder($wsResponse))->create();
    }

    protected function soapCall(string $command, array $parameters, string $parameterName): array
    {
        $settings = $this->getSettings();
        $soapClient = $this->getSoapClient();

        $parameters = array_merge([
            'Usuario' => $settings->username(),
            'Contrasenia' => $settings->password(),
        ], $parameters);

        $wsResponse = $soapClient->execute($command, [
            new \SoapParam([$parameterName => $parameters], $parameterName),
        ]);

        return $wsResponse;
    }
}
