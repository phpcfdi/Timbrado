<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Utils;

use CfdiUtils\Cfdi;

class CfdiData
{
    /** @var string */
    private $uuid;

    /** @var string */
    private $emisorRfc;

    public function __construct(string $uuid, string $emisorRfc)
    {
        $this->uuid = $uuid;
        $this->emisorRfc = $emisorRfc;
    }

    public static function createFromXml(string $xml): self
    {
        $reader = (Cfdi::newFromString($xml))->getQuickReader();
        return new self(
            $reader->complemento->timbreFiscalDigital['UUID'],
            $reader->emisor['Rfc']
        );
    }

    public function uuid(): string
    {
        return $this->uuid;
    }

    public function emisorRfc(): string
    {
        return $this->emisorRfc;
    }
}
