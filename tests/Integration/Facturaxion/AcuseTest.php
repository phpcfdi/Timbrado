<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Tests\Integration\Facturaxion;

use PhpCfdi\Timbrado\Utils\CfdiData;

class AcuseTest extends IntegrationTestCase
{
    public function testAcuse()
    {
        $provider = $this->createFacturaxionWithTestAuth();
        $precfdi = $this->createPreCfdiReadyToTimbrar();

        $timbrar = $provider->timbrar($precfdi);
        $this->assertTrue($timbrar->status()->isSuccess(), 'Cannot "timbrar" a CFDI to perform "Acuse"');
        $cfdiData = CfdiData::createFromXml($timbrar->cfdi());

        $cancelar = $provider->cancelar($cfdiData->emisorRfc(), $cfdiData->uuid());
        $this->assertTrue($cancelar->status()->isSuccess(), 'Cannot "cancelar" a CFDI to perform "Acuse"');

        $acuse = $provider->acuse($cfdiData->emisorRfc(), $cfdiData->uuid());
        $this->assertTrue($acuse->status()->isSuccess());
    }

    public function testAcuseUsingIncorrectUuid()
    {
        $provider = $this->createFacturaxionWithTestAuth();
        $acuse = $provider->acuse('AAA010101AAA', 'CEE4BE01-ADFA-4DEB-8421-ADD60F0BEDAC');
        $this->assertTrue($acuse->status()->isFailure());
    }
}
