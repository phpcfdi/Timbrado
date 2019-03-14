<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Providers\Facturaxion;

use Webmozart\Assert\Assert;

class FacturaxionSettings
{
    /** @var string */
    private $username;

    /** @var string */
    private $password;

    public function __construct(string $username, string $password)
    {
        Assert::notEmpty($username, 'Facturaxion require a non-empty username');
        Assert::notEmpty($password, 'Facturaxion require a non-empty password');
        $this->username = $username;
        $this->password = $password;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function password(): string
    {
        return $this->password;
    }
}
