<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Tests\Integration;

use PhpCfdi\Timbrado\Exceptions\TimbradoRuntimeException;
use PhpCfdi\Timbrado\Providers\NullProvider;
use PhpCfdi\Timbrado\Providers\ProviderInterface;
use PHPUnit\Framework\TestCase;

class CanInitializeANullProviderTest extends TestCase
{
    public function testCreateNullProvider()
    {
        $provider = new NullProvider();
        $this->assertInstanceOf(ProviderInterface::class, $provider);
    }

    public function testTimbrarThrowsException()
    {
        $provider = new NullProvider();

        $this->expectException(TimbradoRuntimeException::class);
        $provider->timbrar('');
    }
}
