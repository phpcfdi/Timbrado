<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Providers\Finkok\ResponseBuilders;

use PhpCfdi\Timbrado\TimbrarResponse;

class TimbrarResponseBuilder extends AbstractResponseBuilder
{
    use TimbrarObtenerResponseBuilderTrait;

    public function getResultName(): string
    {
        return 'stampResult';
    }

    public function create(): TimbrarResponse
    {
        return new TimbrarResponse(
            $this->status(),
            $this->uuid(),
            $this->cfdi(),
            $this->errorMessageFromIncidencias(),
            $this->rawData
        );
    }
}
