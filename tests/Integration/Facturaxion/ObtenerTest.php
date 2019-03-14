<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Tests\Integration\Facturaxion;

class ObtenerTest extends IntegrationTestCase
{
    public function testObtenerNonExistent()
    {
        $otherThanDefaultDate = new \DateTimeImmutable('2019-01-13 14:15:16', new \DateTimeZone('America/Mexico_City'));
        $provider = $this->createFacturaxionWithTestAuth();
        $precfdi = $this->createPreCfdiReadyToTimbrar($otherThanDefaultDate);

        $obtener = $provider->obtener($precfdi);

        $this->assertTrue($obtener->status()->isFailure());
        $this->assertStringStartsWith('WST-24', $obtener->errorMessage(), 'Falló por un error diferente al esperado');
    }

    public function testObtener()
    {
        $provider = $this->createFacturaxionWithTestAuth();
        $precfdi = $this->createPreCfdiReadyToTimbrar();

        $timbrar = $provider->timbrar($precfdi);
        $this->assertTrue($timbrar->status()->isSuccess(), 'Cannot "timbrar" before "obtener"');
        $cfdi = $timbrar->cfdi();

        $obtener = $provider->obtener($cfdi);

        $this->assertTrue($obtener->status()->isSuccess());
    }
}
