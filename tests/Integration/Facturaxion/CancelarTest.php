<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Tests\Integration\Facturaxion;

use PhpCfdi\Timbrado\Utils\CfdiData;

class CancelarTest extends IntegrationTestCase
{
    public function testCancelarSuccess()
    {
        $provider = $this->createFacturaxionWithTestAuth();
        $precfdi = $this->createPreCfdiReadyToTimbrar();

        $timbrar = $provider->timbrar($precfdi);
        $this->assertTrue($timbrar->status()->isSuccess(), 'Cannot "timbrar" a CFDI to perform "Cancelar"');
        $cfdiData = CfdiData::createFromXml($timbrar->cfdi());

        $cancelar = $provider->cancelar($cfdiData->emisorRfc(), $cfdiData->uuid());

        $this->assertTrue($cancelar->status()->isSuccess());
    }

    public function testCancelarUsingIncorrectUuid()
    {
        $provider = $this->createFacturaxionWithTestAuth();
        $cancelar = $provider->cancelar('AAA010101AAA', 'CEE4BE01-ADFA-4DEB-8421-ADD60F0BEDAC');
        $this->assertTrue($cancelar->status()->isFailure());
    }
}
