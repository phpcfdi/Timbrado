<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Tests\Unit\Finkok\ResponseBuilders;

use PhpCfdi\Timbrado\Providers\Finkok\ResponseBuilders\TimbrarResponseBuilder;
use PhpCfdi\Timbrado\Tests\TestCase;

class TimbrarResponseBuilderTest extends TestCase
{
    public function testCreateUsingEmptyResponse()
    {
        $input = [];
        $builder = new TimbrarResponseBuilder($input);

        $response = $builder->create();
        $this->assertTrue($response->status()->isFailure());
        $this->assertSame('', $response->cfdi());
        $this->assertSame('', $response->uuid());
        $this->assertSame('', $response->errorMessage());
        $this->assertSame($input, $response->rawData());
    }

    public function testCreateCorrectResponse()
    {
        $expectedUuid = '0509D06D-E652-4640-9BB7-D4E0AB2B14E1';
        $expectedCfdi = '...';
        $input = [
            'stampResult' => [
                'xml' => $expectedCfdi,
                'UUID' => $expectedUuid,
                'Fecha' => '2019-02-18T22:23:16',
                'CodEstatus' => 'Comprobante timbrado satisfactoriamente',
                'SatSeal' => '...KtDsSiRP9Vw==',
                'Incidencias' => [],
                'NoCertificadoSAT' => '20001000000300022323',
            ],
        ];

        $builder = new TimbrarResponseBuilder($input);

        $this->assertTrue($builder->status()->isSuccess());
        $this->assertSame($expectedCfdi, $builder->cfdi());
        $this->assertSame($expectedUuid, $builder->uuid());

        $response = $builder->create();

        $this->assertTrue($response->status()->isSuccess());
        $this->assertSame($expectedCfdi, $response->cfdi());
        $this->assertSame($expectedUuid, $response->uuid());
        $this->assertSame('', $response->errorMessage());
        $this->assertSame($input, $response->rawData());
    }

    public function testCreateFromArrayUsingAnErrorResponse()
    {
        $input = [
            'stampResult' => [
                'xml' => '',
                'Incidencias' => [
                    'Incidencia' => [
                        'IdIncidencia' => 'ID_incidencia',
                        'Uuid' => '',
                        'CodigoError' => '401',
                        'WorkProcessId' => 'WorkProcessId',
                        'MensajeIncidencia' => 'Fecha y hora de generación fuera de rango',
                        'ExtraInfo' => '',
                        'NoCertificadoPac' => '',
                        'FechaRegistro' => '2019-02-18T22:39:33',
                    ],
                ],
            ],
        ];

        $builder = new TimbrarResponseBuilder($input);

        $this->assertTrue($builder->status()->isFailure());
        $this->assertSame('', $builder->cfdi());
        $this->assertSame('', $builder->uuid());

        $response = $builder->create();

        $this->assertTrue($response->status()->isFailure());
        $this->assertSame('', $response->cfdi());
        $this->assertSame('', $response->uuid());
        $this->assertSame('401: Fecha y hora de generación fuera de rango', $response->errorMessage());
        $this->assertSame($input, $response->rawData());
    }

    public function testCreateFromArrayUsingAnErrorResponseWithMoreThanOneIncidencia()
    {
        /* NOTE: I don't know if this case is really possible, ask Finkok */
        $input = [
            'stampResult' => [
                'xml' => '',
                'Incidencias' => [
                    'Incidencia' => [
                        [
                            'IdIncidencia' => 'ID_incidencia',
                            'Uuid' => '',
                            'CodigoError' => '012',
                            'WorkProcessId' => 'WorkProcessId',
                            'MensajeIncidencia' => 'El emisor no es válido',
                            'ExtraInfo' => '',
                            'NoCertificadoPac' => '',
                            'FechaRegistro' => '2019-02-18T22:39:33',
                        ],
                        [
                            'IdIncidencia' => 'ID_incidencia',
                            'Uuid' => '',
                            'CodigoError' => '401',
                            'WorkProcessId' => 'WorkProcessId',
                            'MensajeIncidencia' => 'Fecha y hora de generación fuera de rango',
                            'ExtraInfo' => '',
                            'NoCertificadoPac' => '',
                            'FechaRegistro' => '2019-02-18T22:39:33',
                        ],
                    ],
                ],
            ],
        ];
        $expectedErrorMessage = implode(PHP_EOL, [
            '012: El emisor no es válido',
            '401: Fecha y hora de generación fuera de rango',
        ]);

        $builder = new TimbrarResponseBuilder($input);

        $this->assertTrue($builder->status()->isFailure());
        $this->assertSame('', $builder->cfdi());
        $this->assertSame('', $builder->uuid());

        $response = $builder->create();

        $this->assertTrue($response->status()->isFailure());
        $this->assertSame('', $response->cfdi());
        $this->assertSame('', $response->uuid());
        $this->assertSame($expectedErrorMessage, $response->errorMessage());
        $this->assertSame($input, $response->rawData());
    }

    public function testStatusSuccess()
    {
        $input = [
            'stampResult' => [
                'CodEstatus' => 'Comprobante timbrado satisfactoriamente',
            ],
        ];
        $builder = new TimbrarResponseBuilder($input);

        $this->assertTrue($builder->status()->isSuccess());
    }

    public function providerStatusFailure()
    {
        return [
            'no result' => [[]],
            'no source' => [['stampResult' => []]],
            'wrong message' => [['stampResult' => ['CodEstatus' => 'Other message']]],
        ];
    }

    /**
     * @param array $input
     * @dataProvider providerStatusFailure
     */
    public function testStatusFailure(array $input)
    {
        $builder = new TimbrarResponseBuilder($input);
        $this->assertTrue($builder->status()->isFailure());
    }
}
