# PhpCfdi/Timbrado

[![Source Code][badge-source]][source]
[![Latest Version][badge-release]][release]
[![Software License][badge-license]][license]
[![Build Status][badge-build]][build]
[![Scrutinizer][badge-quality]][quality]
[![Coverage Status][badge-coverage]][coverage]
[![Total Downloads][badge-downloads]][downloads]
[![SensioLabsInsight][badge-sensiolabs]][sensiolabs]

> Timbrar CFDI con diferentes PAC

PHP Library to connect with Mexican PAC.

This library is primary documented in spanish since this is the intented audience.

PhpCfdi\Timbrado es una librería de PHP para conectar con diferentes PAC.
Utilizando esta librería podrás utilizar objetos que implementan una única interfaz para hacer tareas comunes
de timbrado y cancelación de CFDI 3.3. 

**check [docs/FIRST_STEPS.md][] file and remove this line**


## Installation

Use [composer](https://getcomposer.org/), so please run
```shell
composer require phpcfdi/timbrado
```


## Basic usage

```php
<?php
/** @var \PhpCfdi\Timbrado\Providers\ProviderInterface $provider */
$emisorRfc = 'AAA010101AAA'; // nuestro rfc
$precfdi = '...'; // el pre-cfdi (sin timbre fiscal digital

// timbrar un pre-cfdi
$timbrar = $provider->timbrar($precfdi);
$uuid = $timbrar->uuid();
$cfdi = $timbrar->cfdi(); // cfdi contiene el cfdi timbrado

// obtener el timbre de un precfdi previamente timbrado
$obtener = $provider->timbrar($precfdi);

// realizar una cancelación
$cancelar = $provider->cancelar($emisorRfc, $uuid);

// obtener el acuse de una cancelación
$acuse = $provider->acuse($emisorRfc, $uuid);
```


## PHP Support

Esta librería es compatible con PHP versions 7.2 y va a alinearse con la versión activamente soportada.


## Contributing

Nos gusta el software libre y creemos en sus principios y nos apegánmos al [Código de conducta](./CODE_OF_CONDUCT.md).

Consulta el archivo general de [contribuciones][CONTRIBUTING] (en inglés).
También puedes consultar la guía general de contribuciones de la organización PhpCfdi.

## Copyright and License

The PhpCfdi/Timbrado library is copyright © [Carlos C Soto](https://www.phpcfdi.com/general-information/copyright)
and licensed for use under the MIT License (MIT). Please see [LICENSE][] for more information.


[contributing]: https://github.com/PhpCfdi/Timbrado/blob/master/CONTRIBUTING.md
[changelog]: https://github.com/PhpCfdi/Timbrado/blob/master/docs/CHANGELOG.md
[todo]: https://github.com/PhpCfdi/Timbrado/blob/master/docs/TODO.md

[source]: https://github.com/PhpCfdi/Timbrado
[release]: https://github.com/PhpCfdi/Timbrado/releases
[license]: https://github.com/PhpCfdi/Timbrado/blob/master/LICENSE
[build]: https://travis-ci.org/PhpCfdi/Timbrado?branch=master
[quality]: https://scrutinizer-ci.com/g/PhpCfdi/Timbrado/
[sensiolabs]: https://insight.sensiolabs.com/projects/:INSIGHT_UUID
[coverage]: https://scrutinizer-ci.com/g/PhpCfdi/Timbrado/code-structure/master/code-coverage
[downloads]: https://packagist.org/packages/phpcfdi/timbrado

[badge-source]: http://img.shields.io/badge/source-PhpCfdi/Timbrado-blue.svg?style=flat-square
[badge-release]: https://img.shields.io/github/release/PhpCfdi/Timbrado.svg?style=flat-square
[badge-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[badge-build]: https://img.shields.io/travis/PhpCfdi/Timbrado/master.svg?style=flat-square
[badge-quality]: https://img.shields.io/scrutinizer/g/PhpCfdi/Timbrado/master.svg?style=flat-square
[badge-sensiolabs]: https://insight.sensiolabs.com/projects/:INSIGHT_UUID/mini.png
[badge-coverage]: https://img.shields.io/scrutinizer/coverage/g/PhpCfdi/Timbrado/master.svg?style=flat-square
[badge-downloads]: https://img.shields.io/packagist/dt/phpcfdi/timbrado.svg?style=flat-square
