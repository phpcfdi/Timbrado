<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Tests\Integration\Facturaxion;

use PhpCfdi\Timbrado\Providers\Facturaxion\Facturaxion;
use PhpCfdi\Timbrado\Providers\Facturaxion\FacturaxionSettings;
use PhpCfdi\Timbrado\Providers\ProviderInterface;
use PhpCfdi\Timbrado\Tests\Fakes\FakeSoapClient;

class CanInitializeProviderTest extends IntegrationTestCase
{
    public function testCanCreateProviderAndItExtendsProviderInterface()
    {
        $username = 'username';
        $password = 'password';
        $settings = new FacturaxionSettings($username, $password);
        $soapClient = new FakeSoapClient([]);
        $provider = new Facturaxion($settings, $soapClient);
        $this->assertSame($settings, $provider->getSettings());
        $this->assertSame($soapClient, $provider->getSoapClient());
        $this->assertInstanceOf(ProviderInterface::class, $provider);
    }
}
