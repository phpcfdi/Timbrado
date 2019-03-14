<?php

declare(strict_types=1);

namespace PhpCfdi\Timbrado\Tests;

class TestCase extends \PHPUnit\Framework\TestCase
{
    public static function filePath(string $append = ''): string
    {
        return __DIR__ . '/_files/' . $append;
    }
}
