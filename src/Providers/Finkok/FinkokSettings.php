<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Providers\Finkok;

use PhpCfdi\Timbrado\Exceptions\TimbradoConfigException;

class FinkokSettings
{
    const DEFAULT_CANCEL_METHOD = 'SendSharedPrivateKey';

    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var string */
    private $cancelarMethod;

    /** @var string */
    private $certificate;

    /** @var string */
    private $privateKey;

    /** @var string */
    private $passPhrase;

    /** @var string */
    private $openSslExecutable;

    public function __construct(
        string $username,
        string $password,
        string $cancelarMethod = '',
        string $certificate = '',
        string $privateKey = '',
        string $passPhrase = '',
        string $openSslExecutable = ''
    ) {
        if ('' === $username) {
            throw new TimbradoConfigException('Finkok: Username is empty');
        }
        if ('' === $password) {
            throw new TimbradoConfigException('Finkok: Password is empty');
        }
        $this->username = $username;
        $this->password = $password;
        $this->cancelarMethod = $cancelarMethod ?: self::DEFAULT_CANCEL_METHOD;
        $this->certificate = $certificate;
        $this->privateKey = $privateKey;
        $this->passPhrase = $passPhrase;
        $this->openSslExecutable = $openSslExecutable;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function cancelarMethod(): string
    {
        return $this->cancelarMethod;
    }

    public function certificate(): string
    {
        return $this->certificate;
    }

    public function privateKey(): string
    {
        return $this->privateKey;
    }

    public function passPhrase(): string
    {
        return $this->passPhrase;
    }

    public function openSslExecutable(): string
    {
        return $this->openSslExecutable;
    }
}
