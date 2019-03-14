<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Providers\Facturaxion\ResponseBuilders;

use PhpCfdi\Timbrado\CancelarResponse;
use PhpCfdi\Timbrado\CancelarStatus;

class CancelarResponseBuilder extends AbstractResponseBuilder
{
    public function getResultName(): string
    {
        return 'CancelarObjetoResult';
    }

    public function status(): CancelarStatus
    {
        $statusCode = $this->get('IdValidacion');

        $codesMap = [
            // Cancelable con Aceptación. Comprobante en proceso de cancelación.
            // Espere que el receptor del CFDI acepte/rechace la petición de cancelación.
            'WST-A' => CancelarStatus::PENDING,
            // Cancelado sin Aceptación. En proceso de notificación al SAT.
            'WST-G' => CancelarStatus::SUCCESS,
            // No cancelable
            'WST-L' => CancelarStatus::FAILURE,
            // Cancelable con Aceptación. El comprobante está en proceso de aceptación/rechazo por parte del receptor.
            'WST-B' => CancelarStatus::PENDING,
            // El comprobante está cancelado (por aceptación o por caducidad)
            'WST-C' => CancelarStatus::SUCCESS,
            // Cancelación Rechazada. se procederá a realizar la cancelación una vez más sin caducidad
            'WST-D' => CancelarStatus::PENDING,
            // El Folio Fiscal ha sido marcado para Cancelar, pero no ha sido enviado al SAT
            'WST-E' => CancelarStatus::PENDING,
            // Cancelado por Plazo Vencido
            'WST-F' => CancelarStatus::SUCCESS,
            // Cancelable con Aceptación. El comprobante está en proceso de aceptación/rechazo por parte del receptor.
            'WST-M' => CancelarStatus::PENDING,
        ];
        return new CancelarStatus($codesMap[$statusCode] ?? CancelarStatus::FAILURE);
    }

    public function create(): CancelarResponse
    {
        return new CancelarResponse(
            $this->status(),
            $this->errorMessage(),
            $this->rawData
        );
    }
}
