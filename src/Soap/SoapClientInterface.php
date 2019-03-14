<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Soap;

interface SoapClientInterface
{
    public function execute(string $methodName, array $parameters): array;
}
