<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Tests\Integration\Finkok;

class ObtenerTest extends IntegrationTestCase
{
    public function testObtenerNonExistent()
    {
        $provider = $this->createFinkokProviderForTesting();
        $precfdi = $this->createPreCfdiReadyToTimbrar();

        $obtener = $provider->obtener($precfdi);

        $this->assertTrue($obtener->status()->isFailure());
    }

    public function testObtener()
    {
        $provider = $this->createFinkokProviderForTesting();
        $precfdi = $this->createPreCfdiReadyToTimbrar();

        $timbrar = $provider->timbrar($precfdi);
        $this->assertTrue($timbrar->status()->isSuccess(), 'Cannot "timbrar" before "obtener"');

        // Finkok can take more than 30 seconds to register the cfdi and make it available to cancel

        $sleepTime = 5; // seconds to wait before check again
        $maxtimes = intval(240 / $sleepTime);
        $notFoundMessage = '603: El CFDI no contiene un timbre previo';
        $times = 0;
        do {
            $times = $times + 1;
            sleep($sleepTime);
            $obtener = $provider->obtener($precfdi);
        } while ($obtener->errorMessage() === $notFoundMessage && $times < $maxtimes);

        $this->assertTrue($obtener->status()->isSuccess());
    }
}
