<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Tests\Fakes;

use PhpCfdi\Timbrado\Soap\SoapClientInterface;

class FakeSoapClient implements SoapClientInterface
{
    /** @var array */
    private $executeResponse;

    public function __construct(array $executeResponse)
    {
        $this->executeResponse = $executeResponse;
    }

    public function getExecuteResponse(): array
    {
        return $this->executeResponse;
    }

    public function setExecuteResponse(array $executeResponse)
    {
        $this->executeResponse = $executeResponse;
    }

    public function execute(string $methodName, array $parameters): array
    {
        return $this->getExecuteResponse();
    }
}
