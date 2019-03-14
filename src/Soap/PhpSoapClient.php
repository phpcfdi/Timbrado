<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Soap;

use PhpCfdi\Timbrado\Exceptions\TimbradoRuntimeException;
use SoapClient;
use SoapFault;

class PhpSoapClient implements SoapClientInterface
{
    /** @var string */
    private $endPoint;

    /** @var SoapClient */
    private $soapClient;

    /** @var bool */
    private $tracing;

    /** @var array */
    private $lastTrace;

    public function __construct(string $endPoint, SoapClient $soapClient, bool $tracing = false)
    {
        $this->endPoint = $endPoint;
        $this->soapClient = $soapClient;
        $this->tracing = $tracing;
        $this->lastTrace = [];
    }

    public static function createWithOptions(string $endPoint, array $options, bool $tracing = false): self
    {
        $soap = new SoapClient($endPoint, $options);
        return new static($endPoint, $soap, $tracing);
    }

    public function endPoint(): string
    {
        return $this->endPoint;
    }

    public function soapClient(): SoapClient
    {
        return $this->soapClient;
    }

    public function getLastTrace(): array
    {
        return $this->lastTrace;
    }

    public function tracing(): bool
    {
        return $this->tracing;
    }

    public function execute(string $methodName, array $parameters): array
    {
        $soap = $this->soapClient();
        try {
            $wsResponse = $soap->__soapCall($methodName, $parameters);
        } catch (SoapFault $exception) {
            throw new TimbradoRuntimeException(
                sprintf('Soap call to %s at %s fail: %s', $methodName, $this->endPoint(), $exception->getMessage()),
                0,
                $exception
            );
        } finally {
            if ($this->tracing()) {
                $this->lastTrace = [
                    '$methodName' => $methodName,
                    '$parameters' => $parameters,
                    'Request.Headers' => @$soap->__getLastRequestHeaders(),
                    'Request.Body' => @$soap->__getLastRequest(),
                    'Response.Headers' => @$soap->__getLastResponseHeaders(),
                    'Response.Body' => @$soap->__getLastResponse(),
                ];
            }
        }

        $wsResponse = $wsResponse ?? null;
        if (! ($wsResponse instanceof \stdClass)) {
            throw new TimbradoRuntimeException(
                sprintf('Soap call to %s at %s did not return an object', $methodName, $this->endPoint())
            );
        }
        return json_decode(json_encode($wsResponse) ?: '', true);
    }
}
