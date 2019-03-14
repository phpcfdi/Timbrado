<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Tests\Integration\Finkok;

class TimbrarTest extends IntegrationTestCase
{
    public function testTimbrarWithValidCfdi()
    {
        $precfdi = $this->createPreCfdiReadyToTimbrar();
        $provider = $this->createFinkokProviderForTesting();

        $timbrar = $provider->timbrar($precfdi);

        $this->assertTrue($timbrar->status()->isSuccess());
        $this->assertNotSame('', $timbrar->uuid());
        $this->assertNotSame('', $timbrar->cfdi());
    }

    public function testTimbrarWithInvalidByDateCfdi()
    {
        $invoiceDate = new \DateTimeImmutable('now -5 days', new \DateTimeZone('America/Mexico_City'));
        $precfdi = $this->createPreCfdiReadyToTimbrar($invoiceDate);
        $provider = $this->createFinkokProviderForTesting();

        $timbrar = $provider->timbrar($precfdi);

        $this->assertTrue($timbrar->status()->isFailure());
        $this->assertSame('', $timbrar->uuid());
        $this->assertSame('', $timbrar->cfdi());
        $this->assertStringContainsString('fuera de rango', $timbrar->errorMessage());
    }
}
