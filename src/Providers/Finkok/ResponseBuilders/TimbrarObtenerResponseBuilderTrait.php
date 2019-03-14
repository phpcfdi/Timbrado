<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Providers\Finkok\ResponseBuilders;

use PhpCfdi\Timbrado\BinaryStatus;

/**
 * @var AbstractResponseBuilder $this
 */
trait TimbrarObtenerResponseBuilderTrait
{
    public function status(): BinaryStatus
    {
        $rawStatus = ('Comprobante timbrado satisfactoriamente' === $this->get('CodEstatus'));
        return ($rawStatus) ? BinaryStatus::success() : BinaryStatus::failure();
    }

    public function uuid(): string
    {
        return $this->get('UUID');
    }

    public function cfdi(): string
    {
        return $this->get('xml');
    }

    public function errorMessageFromIncidencias(): string
    {
        $messages = [];

        $findings = $this->getAny('Incidencias');
        if (! is_array($findings)) {
            $findings = [];
        }
        $findings = $findings['Incidencia'] ?? null;
        if (! is_array($findings) || 0 === count($findings)) {
            return '';
        }
        if (isset($findings['CodigoError'])) {
            $findings = [$findings];
        }

        foreach ($findings as $finding) {
            $messages[] = sprintf(
                '%s: %s',
                $finding['CodigoError'] ?? '',
                $finding['MensajeIncidencia'] ?? ''
            );
        }

        return implode(PHP_EOL, $messages);
    }
}
