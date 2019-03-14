<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Providers\Finkok\ResponseBuilders;

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

    protected function get(string $key, string $default = ''): string
    {
        return strval($this->getAny($key, $default));
    }

    protected function getAny(string $key, $default = null)
    {
        return $this->rawData[$this->resultName][$key] ?? $default;
    }
}
