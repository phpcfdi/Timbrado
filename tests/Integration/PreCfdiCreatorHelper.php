<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Tests\Integration;

use CfdiUtils\Certificado\Certificado;
use CfdiUtils\CfdiCreator33;
use CfdiUtils\Utils\Format;

class PreCfdiCreatorHelper
{
    /** @var \DateTimeImmutable */
    private $invoiceDate;

    /** @var float */
    private $conceptoAmount;

    /** @var string */
    private $emisorRfc;

    /** @var string */
    private $cerFile;

    /** @var string */
    private $keyFile;

    /** @var string */
    private $passPhrase;

    public function __construct(
        string $emisorRfc,
        string $cerFile,
        string $keyFile,
        string $passPhrase
    ) {
        $this->emisorRfc = $emisorRfc;
        $this->cerFile = $cerFile;
        $this->keyFile = $keyFile;
        $this->passPhrase = $passPhrase;
        $this->invoiceDate = new \DateTimeImmutable('now -5 minutes', new \DateTimeZone('America/Mexico_City'));
        $this->conceptoAmount = round(random_int(1000, 10000) + random_int(0, 99) / 100, 2);
    }

    public function getInvoiceDate(): \DateTimeImmutable
    {
        return $this->invoiceDate;
    }

    public function setInvoiceDate(\DateTimeImmutable $invoiceDate)
    {
        $this->invoiceDate = $invoiceDate;
    }

    public function getConceptoAmount(): float
    {
        return $this->conceptoAmount;
    }

    public function setConceptoAmount(float $conceptoAmount)
    {
        $this->conceptoAmount = $conceptoAmount;
    }

    public function getEmisorRfc(): string
    {
        return $this->emisorRfc;
    }

    public function getCerFile(): string
    {
        return $this->cerFile;
    }

    public function getKeyFile(): string
    {
        return $this->keyFile;
    }

    public function getPassPhrase(): string
    {
        return $this->passPhrase;
    }

    public function create(): string
    {
        $creator = new CfdiCreator33();

        $comprobante = $creator->comprobante();
        $comprobante->addAttributes([
            'Fecha' => $this->getInvoiceDate()->format('Y-m-d\TH:i:s'),
            'FormaPago' => '01', // efectivo
            'Moneda' => 'MXN',
            'TipoDeComprobante' => 'I', // ingreso
            'MetodoPago' => 'PUE',
            'LugarExpedicion' => '86000',
        ]);
        $comprobante->addEmisor([
            'Rfc' => $this->getEmisorRfc(),
            'Nombre' => 'ACCEM SERVICIOS EMPRESARIALES SC',
            'RegimenFiscal' => '601',
        ]);
        $comprobante->addReceptor([
            'Rfc' => 'XAXX010101000',
            'UsoCFDI' => 'G03', // gastos en general
        ]);
        $comprobante->addConcepto([
            'ClaveProdServ' => '52161557', // Consola portátil de juegos de computador
            'NoIdentificacion' => 'GAMEPAD007',
            'Cantidad' => '4',
            'ClaveUnidad' => 'H87', // Pieza
            'Unidad' => 'PIEZA',
            'Descripcion' => 'Portable tetris gamepad pro++ ⏻',
            'ValorUnitario' => Format::number($this->getConceptoAmount() / 4, 2),
            'Importe' => Format::number($this->getConceptoAmount(), 2),
            'Descuento' => Format::number($this->getConceptoAmount() / 4, 2), // hot sale: take 4, pay 3
        ])->addTraslado([
            'Base' => Format::number(3 * $this->getConceptoAmount() / 4, 2),
            'Impuesto' => '002', // IVA
            'TipoFactor' => 'Tasa',
            'TasaOCuota' => '0.160000',
            'Importe' => Format::number(3 * 0.16 * $this->getConceptoAmount() / 4, 2),
        ]);

        $creator->addSumasConceptos();
        $creator->putCertificado(new Certificado($this->getCerFile()), false);
        $creator->addSello('file://' . $this->getKeyFile(), $this->getPassPhrase());

        return $creator->asXml();
    }
}
