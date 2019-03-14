<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Tests\Unit\Finkok\ResponseBuilders;

use PhpCfdi\Timbrado\Providers\Finkok\ResponseBuilders\CancelarResponseBuilder;
use PhpCfdi\Timbrado\Tests\TestCase;

class CancelarResponseBuilderTest extends TestCase
{
    public function testCreateUsingEmptyResponse()
    {
        $input = [];
        $builder = new CancelarResponseBuilder($input);

        $response = $builder->create();
        $this->assertTrue($response->status()->isFailure());
        $this->assertSame('', $response->errorMessage());
        $this->assertSame($input, $response->rawData());
    }

    public function testCreateUsingResponseNotFoundMessage()
    {
        $input = ['cancelResult' => ['CodEstatus' => 'UUID: CEE4BE01-ADFA-4DEB-8421-ADD60F0BEDAC No Encontrado']];
        $builder = new CancelarResponseBuilder($input);

        $response = $builder->create();
        $this->assertTrue($response->status()->isFailure());
        $this->assertSame('UUID No encontrado', $response->errorMessage());
    }

    public function testCreateUsingResponseCode()
    {
        $input = ['cancelResult' => ['CodEstatus' => '310']];
        $builder = new CancelarResponseBuilder($input);

        $response = $builder->create();
        $this->assertTrue($response->status()->isFailure());
        $this->assertStringStartsWith('310: ', $response->errorMessage());
    }

    public function testCreateUsingUuidCode()
    {
        $input = ['cancelResult' => ['Folios' => ['Folio' => [
            'EstatusUUID' => '205',
        ]]]];
        $builder = new CancelarResponseBuilder($input);

        $response = $builder->create();
        $this->assertTrue($response->status()->isFailure());
        $this->assertStringStartsWith('205: ', $response->errorMessage());
    }

    public function testCreateUsingUuidCodeNoCancelable()
    {
        $input = ['cancelResult' => ['Folios' => ['Folio' => [
            'EstatusUUID' => 'no_cancelable',
        ]]]];
        $builder = new CancelarResponseBuilder($input);

        $response = $builder->create();
        $this->assertTrue($response->status()->isFailure());
        $this->assertStringStartsWith('no_cancelable: ', $response->errorMessage());
    }

    public function testCreateCanceladoSinAceptacionAhora()
    {
        $input = ['cancelResult' => ['Folios' => ['Folio' => [
            'EstatusUUID' => '201',
        ]]]];
        $builder = new CancelarResponseBuilder($input);

        $response = $builder->create();
        $this->assertTrue($response->status()->isSuccess());
        $this->assertSame('', $response->errorMessage());
    }

    public function testCreateCanceladoSinAceptacionPreviamente()
    {
        $input = ['cancelResult' => ['Folios' => ['Folio' => [
            'EstatusUUID' => '202',
        ]]]];
        $builder = new CancelarResponseBuilder($input);

        $response = $builder->create();
        $this->assertTrue($response->status()->isSuccess());
        $this->assertSame('', $response->errorMessage());
    }

    public function testCreatePendiente()
    {
        $input = ['cancelResult' => ['Folios' => ['Folio' => [
            'EstatusUUID' => '201',
            'EstatusCancelacion' => 'En proceso',
        ]]]];
        $builder = new CancelarResponseBuilder($input);

        $response = $builder->create();
        $this->assertTrue($response->status()->isPending());
        $this->assertSame('', $response->errorMessage());
    }
}
