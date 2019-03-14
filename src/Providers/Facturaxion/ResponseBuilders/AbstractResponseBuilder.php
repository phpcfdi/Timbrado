<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Providers\Facturaxion\ResponseBuilders;

abstract class AbstractResponseBuilder
{
    /** @var string */
    private $resultName;

    /** @var array */
    protected $rawData;

    public function __construct(array $rawData)
    {
        $this->rawData = $rawData;
        $this->resultName = $this->getResultName();
    }

    abstract protected function getResultName(): string;

    protected function get(string $key, $default = ''): string
    {
        return strval($this->rawData[$this->resultName][$key] ?? $default);
    }

    public function errorMessage(
        string $keyIdValidacion = 'IdValidacion',
        string $keyMensajeValidacion = 'MensajeValidacion'
    ): string {
        $idValidacion = $this->get($keyIdValidacion);
        $mensajeValidacion = $this->get($keyMensajeValidacion);
        if ('' === $idValidacion && '' === $mensajeValidacion) {
            return '';
        }
        return sprintf('%s: %s', $idValidacion ?: '00000', $mensajeValidacion ?: '(sin mensaje de validación)');
    }
}
