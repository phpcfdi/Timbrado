<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Tests\Integration\Finkok;

use PhpCfdi\Timbrado\Providers\Finkok\Finkok;
use PhpCfdi\Timbrado\Providers\Finkok\FinkokFactory;
use PhpCfdi\Timbrado\Providers\Finkok\FinkokSettings;
use PhpCfdi\Timbrado\Tests\Integration\BaseIntegrationTestCase;

class IntegrationTestCase extends BaseIntegrationTestCase
{
    public function getConfigEmisorRfc(): string
    {
        return strval(getenv('FINKOK_EMISORRFC'));
    }

    public function createFinkokProviderForTesting(array $override = []): Finkok
    {
        return new Finkok(
            FinkokFactory::createUsingDevelopmentWebservices(
                new FinkokSettings(
                    strval($override['FINKOK_USERNAME'] ?? getenv('FINKOK_USERNAME')),
                    strval($override['FINKOK_PASSWORD'] ?? getenv('FINKOK_PASSWORD')),
                    strval($override['FINKOK_CANCEL_METHOD'] ?? getenv('FINKOK_CANCEL_METHOD')),
                    $this->readFileContents(strval($override['FINKOK_CERPEMFILE'] ?? getenv('FINKOK_CERPEMFILE'))),
                    $this->readFileContents(strval($override['FINKOK_KEYPEMFILE'] ?? getenv('FINKOK_KEYPEMFILE'))),
                    strval($override['FINKOK_KEY_PASSPHRASE'] ?? getenv('FINKOK_KEY_PASSPHRASE')),
                    strval($override['OPENSSL_EXECUTABLE'] ?? getenv('OPENSSL_EXECUTABLE'))
                )
            )
        );
    }

    private function readFileContents(string $filepath): string
    {
        if ('' === $filepath) {
            return '';
        }

        if (0 !== strpos($filepath, '/')) {
            $filepath = $this->filePath($filepath);
        }

        return strval(file_get_contents($filepath));
    }
}
