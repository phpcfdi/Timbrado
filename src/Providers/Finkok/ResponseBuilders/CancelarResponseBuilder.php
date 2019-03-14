<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Providers\Finkok\ResponseBuilders;

use PhpCfdi\Timbrado\CancelarResponse;
use PhpCfdi\Timbrado\CancelarStatus;

class CancelarResponseBuilder extends AbstractResponseBuilder
{
    private $errorMessage;

    private $status;

    public function __construct(array $rawData)
    {
        parent::__construct($rawData);

        $inputFolio = $this->getAny('Folios')['Folio'] ?? [];
        $this->errorMessage = '';
        $this->status = $this->checkStatuses(
            $this->get('CodEstatus'), // response code
            strval($inputFolio['EstatusUUID'] ?? ''), // uuidCode
            strval($inputFolio['EstatusCancelacion'] ?? '') // cancel status
        );
    }

    public function getResultName(): string
    {
        return 'cancelResult';
    }

    public function checkStatuses($responseCode, $uuidCode, $cancelStatus): CancelarStatus
    {
        if ('' === $responseCode && '' === $uuidCode && '' === $cancelStatus) {
            return CancelarStatus::failure();
        }

        if ('No Encontrado' === substr($responseCode, - 13)) {
            $this->errorMessage = 'UUID No encontrado';
            return CancelarStatus::failure();
        }

        $failureResponseCodes = [
            '300' => 'Usuario no válido',
            '301' => 'XML mal formado',
            '302' => 'Sello mal formado',
            '304' => 'Certificado revocado o caduco',
            '305' => 'Certificado inválido',
            '309' => 'Patrón de folio inválido',
            '310' => 'Se encuentra usando certificados tipo FIEL y no de CSD',
        ];
        if (array_key_exists($responseCode, $failureResponseCodes)) {
            $this->errorMessage = sprintf('%s: %s', $responseCode, $failureResponseCodes[$responseCode]);
            return CancelarStatus::failure();
        }

        $failureUuidCodes = [
            '203' => 'No corresponde el RFC del Emisor y de quien solicita la cancelación',
            '205' => 'UUID No encontrado',
            'no_cancelable' => 'El UUID contiene CFDI relacionados',
        ];
        if (array_key_exists($uuidCode, $failureUuidCodes)) {
            $this->errorMessage = sprintf('%s: %s', $uuidCode, $failureUuidCodes[$uuidCode]);
            return CancelarStatus::failure();
        }

        if ('201' === $uuidCode && 'En proceso' === $cancelStatus) {
            return CancelarStatus::pending();
        }

        $statusUuidCodesSuccess = [
            '201' => 'Petición de cancelación realizada exitosamente',
            '202' => 'Petición de cancelación realizada previamente',
        ];
        if (array_key_exists($uuidCode, $statusUuidCodesSuccess)) {
            return CancelarStatus::success();
        }

        $this->errorMessage = implode(PHP_EOL, [
            'ERR: No se pudieron interpretar los estados para reconocer el resultado de la solicitud',
            sprintf('Estado respuesta: %s', $responseCode ?: '(ninguno)'),
            sprintf('Estado UUID: %s', $uuidCode ?: '(ninguno)'),
            sprintf('Estado cancelación: %s', $cancelStatus ?: '(ninguno)'),
        ]);
        return CancelarStatus::failure();
    }

    public function status(): CancelarStatus
    {
        return $this->status;
    }

    public function errorMessage(): string
    {
        return $this->errorMessage;
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
