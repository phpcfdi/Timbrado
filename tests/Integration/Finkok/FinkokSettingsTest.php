<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Tests\Integration\Finkok;

use PhpCfdi\Timbrado\Exceptions\TimbradoConfigException;
use PhpCfdi\Timbrado\Providers\Finkok\FinkokSettings;

class FinkokSettingsTest extends IntegrationTestCase
{
    public function testConstructAndProperties()
    {
        $settings = new FinkokSettings(
            'usuario',
            'contraseña',
            'metodo-de-cancelación',
            'certificado',
            'llave-privada',
            'llave-contraseña',
            '/usr/local/bin/openssl'
        );

        $this->assertSame('usuario', $settings->username());
        $this->assertSame('contraseña', $settings->password());
        $this->assertSame('metodo-de-cancelación', $settings->cancelarMethod());
        $this->assertSame('certificado', $settings->certificate());
        $this->assertSame('llave-privada', $settings->privateKey());
        $this->assertSame('llave-contraseña', $settings->passPhrase());
        $this->assertSame('/usr/local/bin/openssl', $settings->openSslExecutable());
    }

    public function testWithMinimalArguments()
    {
        new FinkokSettings('x', 'y');
        $this->assertTrue(true, 'It was not expected an exception setting only username and password');
    }

    public function testEmptyUsernameThrowsException()
    {
        $this->expectException(TimbradoConfigException::class);
        $this->expectExceptionMessage('Username');

        new FinkokSettings('', 'bar');
    }

    public function testEmptyPasswordThrowsException()
    {
        $this->expectException(TimbradoConfigException::class);
        $this->expectExceptionMessage('Password');

        new FinkokSettings('foo', '');
    }
}
