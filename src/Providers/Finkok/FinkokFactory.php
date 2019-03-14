<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Providers\Finkok;

use CfdiUtils\OpenSSL\OpenSSL;
use PhpCfdi\Timbrado\Exceptions\TimbradoConfigException;
use PhpCfdi\Timbrado\Soap\PhpSoapClient;
use PhpCfdi\Timbrado\Soap\SoapClientInterface;

class FinkokFactory
{
    const ACTION_TIMBRADO = 'T';

    const ACTION_CANCEL = 'C';

    const ACTION_OBTAIN = 'O';

    const WS_TIMBRAR_PRODUCTION = 'https://facturacion.finkok.com/servicios/soap/stamp.wsdl';

    const WS_TIMBRAR_DEVELOPMENT = 'https://demo-facturacion.finkok.com/servicios/soap/stamp.wsdl';

    const WS_CANCEL_PRODUCTION = 'https://facturacion.finkok.com/servicios/soap/cancel.wsdl';

    const WS_CANCEL_DEVELOPMENT = 'https://demo-facturacion.finkok.com/servicios/soap/cancel.wsdl';

    const WS_UTILITIES_PRODUCTION = 'https://facturacion.finkok.com/servicios/soap/utilities.wsdl';

    const WS_UTILITIES_DEVELOPMENT = 'https://demo-facturacion.finkok.com/servicios/soap/utilities.wsdl';

    private $servicesUrls = [];

    /** @var FinkokSettings */
    private $currentSettings;

    /** @var OpenSSL */
    private $openSsl;

    /**
     * @param FinkokSettings $currentSettings
     * @param string[] $servicesUrls
     * @internal
     */
    public function __construct(FinkokSettings $currentSettings, array $servicesUrls)
    {
        $this->currentSettings = $currentSettings;
        $this->servicesUrls = $servicesUrls;
        $this->openSsl = $this->createDefaultOpenSsl();
    }

    public function createDefaultOpenSsl(): OpenSSL
    {
        return new OpenSSL($this->getCurrentSettings()->openSslExecutable());
    }

    public function getOpenSsl(): OpenSSL
    {
        return $this->openSsl;
    }

    public static function createUsingProductionWebservices(FinkokSettings $currentSettings): self
    {
        return new self($currentSettings, [
            static::ACTION_TIMBRADO => static::WS_TIMBRAR_PRODUCTION,
            static::ACTION_OBTAIN => static::WS_TIMBRAR_PRODUCTION,
            static::ACTION_CANCEL => static::WS_CANCEL_PRODUCTION,
        ]);
    }

    public static function createUsingDevelopmentWebservices(FinkokSettings $currentSettings): self
    {
        return new self($currentSettings, [
            static::ACTION_TIMBRADO => static::WS_TIMBRAR_DEVELOPMENT,
            static::ACTION_OBTAIN => static::WS_TIMBRAR_DEVELOPMENT,
            static::ACTION_CANCEL => static::WS_CANCEL_DEVELOPMENT,
        ]);
    }

    public function getServiceUrl(string $action): string
    {
        return $this->servicesUrls[$action] ?? '';
    }

    public function createSoapClient(string $action): SoapClientInterface
    {
        return PhpSoapClient::createWithOptions($this->getServiceUrl($action), $this->defaultSoapOptions(), true);
    }

    public function createSoapClientForTimbrar(): SoapClientInterface
    {
        return $this->createSoapClient(self::ACTION_TIMBRADO);
    }

    public function createSoapClientForCancelar(): SoapClientInterface
    {
        return $this->createSoapClient(self::ACTION_CANCEL);
    }

    public function createSoapClientForObtener(): SoapClientInterface
    {
        return $this->createSoapClient(self::ACTION_OBTAIN);
    }

    private function defaultSoapOptions(): array
    {
        return [
            'soap_version' => SOAP_1_1,
            'cache_wsdl' => WSDL_CACHE_MEMORY,
            'exceptions' => true, // whether soap errors throw exceptions of type SoapFault
            'stream_context' => stream_context_create(['ssl' => ['verify_peer' => true]]),
            'keep_alive' => false, // whether to send the Connection: Keep-Alive header or Connection: close
            'trace' => true,
        ];
    }

    public function getCurrentSettings(): FinkokSettings
    {
        return $this->currentSettings;
    }

    public function createCancelarProcessor(): Processors\Cancelar\CancelarProcessorInterface
    {
        $settings = $this->getCurrentSettings();
        $cancelarMethod = $settings->cancelarMethod();
        if ('SendSharedPrivateKey' === $cancelarMethod) {
            return $this->createCancelarBySendSharedPrivateKey();
        }
        if ('PublishedSharedPrivateKey' === $cancelarMethod) {
            return $this->createCancelarByPublishedSharedPrivateKey();
        }
        if ('ConvertToSharedPrivateKey' === $cancelarMethod) {
            return $this->createCancelarByConvertToSharedPrivateKey();
        }
        // TODO: Crear este método
        // if ('CreateSignature' === $cancelarMethod) {
        // }
        throw new TimbradoConfigException('Finkok: Invalid cancelar method');
    }

    public function createCancelarBySendSharedPrivateKey(): Processors\Cancelar\SendSharedPrivateKey
    {
        return new Processors\Cancelar\SendSharedPrivateKey(
            $this->createSoapClientForCancelar(),
            $this->getCurrentSettings()
        );
    }

    public function createCancelarByPublishedSharedPrivateKey(): Processors\Cancelar\PublishedSharedPrivateKey
    {
        return new Processors\Cancelar\PublishedSharedPrivateKey(
            $this->createSoapClientForCancelar(),
            $this->getCurrentSettings()
        );
    }

    public function createCancelarByConvertToSharedPrivateKey(): Processors\Cancelar\ConvertToSharedPrivateKey
    {
        return new Processors\Cancelar\ConvertToSharedPrivateKey(
            $this->createSoapClientForCancelar(),
            $this->getCurrentSettings(),
            $this->getOpenSsl()
        );
    }
}
