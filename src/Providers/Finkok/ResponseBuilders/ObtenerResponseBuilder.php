<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Providers\Finkok\ResponseBuilders;

use PhpCfdi\Timbrado\ObtenerResponse;

class ObtenerResponseBuilder extends AbstractResponseBuilder
{
    use TimbrarObtenerResponseBuilderTrait;

    public function getResultName(): string
    {
        return 'stampedResult';
    }

    public function create(): ObtenerResponse
    {
        return new ObtenerResponse(
            $this->status(),
            $this->uuid(),
            $this->cfdi(),
            $this->errorMessageFromIncidencias(),
            $this->rawData
        );
    }
}
