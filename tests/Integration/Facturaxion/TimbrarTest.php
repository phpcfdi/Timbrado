<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Tests\Integration\Facturaxion;

use PhpCfdi\Timbrado\Utils\CfdiData;

class TimbrarTest extends IntegrationTestCase
{
    public function testTimbrarWithNonXmlAsCfdi()
    {
        $precfdi = 'foo';
        $provider = $this->createFacturaxionWithTestAuth();

        $timbrarResponse = $provider->timbrar($precfdi);

        $this->assertTrue($timbrarResponse->status()->isFailure());
        $this->assertSame('', $timbrarResponse->uuid());
        $this->assertStringContainsString('WST-33', $timbrarResponse->errorMessage());
        $this->assertStringContainsString(
            'El XML del PreCFDI para ser sellado no está formado correctamente.',
            $timbrarResponse->errorMessage()
        );
    }

    public function testTimbrarWithInvalidByDateCfdi()
    {
        $invoiceDate = new \DateTimeImmutable('now -5 days', new \DateTimeZone('America/Mexico_City'));
        $precfdi = $this->createPreCfdiReadyToTimbrar($invoiceDate);
        $provider = $this->createFacturaxionWithTestAuth();

        $timbrarResponse = $provider->timbrar($precfdi);

        $this->assertTrue($timbrarResponse->status()->isFailure());
        $this->assertSame('', $timbrarResponse->uuid());
        $this->assertStringStartsWith('WST-20', $timbrarResponse->errorMessage());
        $this->assertStringContainsString('Fecha fuera de rango para timbrado', $timbrarResponse->errorMessage());
    }

    public function testTimbrarWithValidCfdi()
    {
        $precfdi = $this->createPreCfdiReadyToTimbrar();
        $provider = $this->createFacturaxionWithTestAuth();

        $timbrarResponse = $provider->timbrar($precfdi);

        $this->assertTrue($timbrarResponse->status()->isSuccess());
        $this->assertNotSame('', $timbrarResponse->uuid());
        $cfdiData = CfdiData::createFromXml($timbrarResponse->cfdi());
        $this->assertSame($timbrarResponse->uuid(), $cfdiData->uuid());
    }
}
