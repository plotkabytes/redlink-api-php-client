# Redlink PHP API Client

![Build and tests](https://github.com/plotkabytes/redlink-api-php-client/actions/workflows/ci.yml/badge.svg)
[![GitHub license](https://img.shields.io/github/license/Naereen/StrapDown.js.svg)](https://github.com/plotkabytes/redlink-api-php-client/blob/main/LICENSE)
[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg?style=flat-square)](http://makeapullrequest.com)

We present simple [RedlinkAPI v2.1](https://docs.redlink.pl/) client for PHP.

This client is strongly based on [GitLab PHP API Client](https://github.com/GitLabPHP/Client).

Check out the:

[Change log](CHANGELOG.md)

[License](LICENSE)

## Installation

This version supports [PHP](https://php.net) >= 7.2. To get started, simply require the project
using [Composer](https://getcomposer.org). You will also need to install packages that "
provide" [`psr/http-client-implementation`](https://packagist.org/providers/psr/http-client-implementation)
and [`psr/http-factory-implementation`](https://packagist.org/providers/psr/http-factory-implementation).

### Standard Installation

To install the client, you will need to be using Composer in your project. 
Here's how to install composer:

```bash
curl -sS https://getcomposer.org/installer | php
```

After getting composer you have to install two packages - 
this repository and PSR HTTP client implementation 
(for example 
[Guzzle](https://github.com/guzzle/guzzle) / 
[Buzz](https://github.com/kriswallsmith/Buzz)):

```bash
$ composer require plotkabytes/redlink-api-php-client guzzlehttp/guzzle
```

This project is using following PSR's:

* [PSR-7](https://www.php-fig.org/psr/psr-7/)
* [PSR-17](https://www.php-fig.org/psr/psr-17/)
* [PSR-18](https://www.php-fig.org/psr/psr-18/)
* [HTTPlug](https://httplug.io/)

## General library usage

Example script:

```php
<?php

// Use composer autoloader
require __DIR__ . '/../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface;
use Plotkabytes\RedlinkApi\Utils\ResponseTransformer;

// Create client
$client = new \Redlink\DefaultClient();

// Setup authentication
$client->setAuthentication("ENTER_API_KEY_HERE", "ENTER_APPLICATION_KEY_HERE"); // authentication v2

// Do something
$response = $client->groups()->list();

// Optional, transform response to objects
$responseTransformer = new ResponseTransformer();

/**
 * @var $parsedResponse RedlinkResponse 
 */
$parsedResponse = $responseTransformer->createFromJson($response->getBody());

print_r($parsedResponse->getData());
print_r($parsedResponse->getErrors());;
print_r($parsedResponse->getMeta());
```

If you are not sure how to use given method from this library then check our [examples](examples).

Details of API methods can be found [on official documentation pages](https://docs.redlink.pl/).

## Using plugins

This library supports [plugins](https://docs.php-http.org/en/latest/plugins/index.html).

The plugin system allows to wrap a Client and add some processing logic prior to and/or after sending the actual request.


## Testing

```bash
$ docker-compose up -d
$ docker exec -it redlink-api-php-lib /bin/bash
$ composer install
$ php vendor/bin/phpstan analyze -c /var/www/html/phpstan.neon.dist
$ php vendor/bin/phpunit
$ php vendor/bin/psalm.phar
$ composer auto-format
```

## Framework integration

### Symfony support

Symfony support is provided by [this package](https://github.com/plotkabytes/redlink-api-bundle).

It supports Symfony >= 5.3 and PHP >= 7.2.

### Laravel support

TODO

## Versioning

We use [Semantic Versioning 2.0.0](https://semver.org/).

Given a version number MAJOR.MINOR.PATCH, increment the:

* MAJOR version when you make incompatible API changes,
* MINOR version when you add functionality in a backwards compatible manner, and
* PATCH version when you make backwards compatible bug fixes.

Additional labels for pre-release and build metadata are available as extensions to the MAJOR.MINOR.PATCH format.

## Contributing

We will gladly receive issue reports and review and accept pull requests.
Feel free to contribute in any way.

## Author

Mateusz Żyła <mateusz.zylaa@gmail.com>

## License

Redlink PHP API Client is licensed under [The MIT License (MIT)](LICENSE).
