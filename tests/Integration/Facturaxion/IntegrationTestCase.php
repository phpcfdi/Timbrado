<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Tests\Integration\Facturaxion;

use PhpCfdi\Timbrado\Providers\Facturaxion\Facturaxion;
use PhpCfdi\Timbrado\Providers\Facturaxion\FacturaxionSettings;
use PhpCfdi\Timbrado\Tests\Integration\BaseIntegrationTestCase;

class IntegrationTestCase extends BaseIntegrationTestCase
{
    public function getConfigEmisorRfc(): string
    {
        return 'AAA010101AAA';
    }

    public function createFacturaxionWithTestAuth(): Facturaxion
    {
        return new Facturaxion(
            new FacturaxionSettings('Demo', '123456'),
            Facturaxion::createDefaultSoapClient(Facturaxion::WS_DEVELOPMENT)
        );
    }
}
