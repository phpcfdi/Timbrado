<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Tests\Integration\Finkok;

use PhpCfdi\Timbrado\Providers\Finkok\Finkok;
use PhpCfdi\Timbrado\Providers\Finkok\FinkokFactory;
use PhpCfdi\Timbrado\Providers\ProviderInterface;

class CanInitializeProviderTest extends IntegrationTestCase
{
    public function testCanCreateProviderAndItExtendsProviderInterface()
    {
        /** @var FinkokFactory&\PHPUnit\Framework\MockObject\MockObject $factory */
        $factory = $this->createMock(FinkokFactory::class);
        $provider = new Finkok($factory);
        $this->assertSame($factory, $provider->getFactory());
        $this->assertInstanceOf(ProviderInterface::class, $provider);
    }
}
