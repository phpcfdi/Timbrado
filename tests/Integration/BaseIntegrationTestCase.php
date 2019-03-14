<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Tests\Integration;

use PhpCfdi\Timbrado\Tests\TestCase;

abstract class BaseIntegrationTestCase extends TestCase
{
    abstract public function getConfigEmisorRfc(): string;

    public function createPrecfdiCreatorHelper(): PreCfdiCreatorHelper
    {
        return new PreCfdiCreatorHelper(
            $this->getConfigEmisorRfc(),
            $this->filePath('certificates/CSD01_AAA010101AAA.cer'),
            $this->filePath('certificates/CSD01_AAA010101AAA.key.pem'),
            trim(strval(file_get_contents($this->filePath('certificates/CSD01_AAA010101AAA.key.txt'))))
        );
    }

    public function createPreCfdiReadyToTimbrar(
        \DateTimeImmutable $invoiceDate = null,
        float $conceptoAmount = null
    ): string {
        $helper = $this->createPrecfdiCreatorHelper();
        if (null !== $invoiceDate) {
            $helper->setInvoiceDate($invoiceDate);
        }
        if (null !== $conceptoAmount) {
            $helper->setConceptoAmount($conceptoAmount);
        }

        return $helper->create();
    }
}
