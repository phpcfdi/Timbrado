<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Tests\Integration\Finkok;

use PhpCfdi\Timbrado\Utils\CfdiData;

class CancelarTest extends IntegrationTestCase
{
    public function testCancelar()
    {
        $provider = $this->createFinkokProviderForTesting([
            'FINKOK_CANCEL_METHOD' => 'ConvertToSharedPrivateKey',
        ]);
        $precfdi = $this->createPreCfdiReadyToTimbrar();

        $timbrar = $provider->timbrar($precfdi);
        $this->assertTrue($timbrar->status()->isSuccess(), 'Cannot "timbrar" a CFDI to perform "Cancelar"');
        $cfdiData = CfdiData::createFromXml($timbrar->cfdi());

        // Finkok can take more than 30 seconds to register the cfdi and make it available to cancel

        $sleepTime = 5; // seconds to wait before check again
        $maxtimes = intval(240 / $sleepTime);
        $notFoundMessage = 'UUID No encontrado';
        $times = 0;
        do {
            $times = $times + 1;
            sleep($sleepTime);
            $cancelar = $provider->cancelar($cfdiData->emisorRfc(), $cfdiData->uuid());
        } while (false !== strpos($cancelar->errorMessage(), $notFoundMessage) && $times < $maxtimes);

        $this->assertTrue($cancelar->status()->isSuccess());
    }

    public function testCancelarUsingIncorrectUuid()
    {
        $provider = $this->createFinkokProviderForTesting([
            'FINKOK_CANCEL_METHOD' => 'ConvertToSharedPrivateKey',
        ]);
        $cancelar = $provider->cancelar('AAA010101AAA', 'CEE4BE01-ADFA-4DEB-8421-ADD60F0BEDAC');
        $this->assertTrue($cancelar->status()->isFailure());
    }
}
