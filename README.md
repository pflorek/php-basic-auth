# PHP Basic Auth

[![Build Status](https://travis-ci.org/pflorek/php-basic-auth.svg?branch=master)](https://travis-ci.org/pflorek/php-basic-auth)
[![Coverage Status](https://coveralls.io/repos/github/pflorek/php-basic-auth/badge.svg?branch=master)](https://coveralls.io/github/pflorek/php-basic-auth?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/pflorek/php-basic-auth/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/pflorek/php-basic-auth/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/pflorek/php-basic-auth/v/stable)](https://packagist.org/packages/pflorek/php-basic-auth)
[![Total Downloads](https://poser.pugx.org/pflorek/php-basic-auth/downloads)](https://packagist.org/packages/pflorek/php-basic-auth)
[![Latest Unstable Version](https://poser.pugx.org/pflorek/php-basic-auth/v/unstable)](https://packagist.org/packages/pflorek/php-basic-auth)
[![License](https://poser.pugx.org/pflorek/php-basic-auth/license)](https://packagist.org/packages/pflorek/php-basic-auth)
[![Monthly Downloads](https://poser.pugx.org/pflorek/php-basic-auth/d/monthly)](https://packagist.org/packages/pflorek/php-basic-auth)
[![Daily Downloads](https://poser.pugx.org/pflorek/php-basic-auth/d/daily)](https://packagist.org/packages/pflorek/php-basic-auth)
[![composer.lock](https://poser.pugx.org/pflorek/php-basic-auth/composerlock)](https://packagist.org/packages/pflorek/php-basic-auth)

This library provides a simple way to get or set credentials (username, password) on a PSR-7 `RequestInterface`. Also it helps challenging an unauthorized client by adding the 'WWW-authenticate' header line with status code 401 to a PSR-7 `ResponseInterface`. It should be helpful if a PSR-15 `Middleware` is not applicable.

* There is no validation if username is correct (should not contain `:`).
* Also there is no validation if basic credentials are properly base64 encoded.
* Omitted `Authorization` header line or missing basic credentials will return `null` credentials.
* Can only challenge for `Basic Auth`. `Digest` is currently not supported.
* For backward compatibility for PHP >= 5.4 PSR-17 HTTP factories currently not supported.
* Should comply with [RFC 7617].

## Usage

### Obtain credentials

Obtain credentials (username, password) from PSR-7 request interface.
```PHP

use Psr\Http\Message\RequestInterface;
use \PFlorek\BasicAuth\BasicAuth;

//Given request with header line 'Authorization: Basic QWxhZGRpbjpvcGVuIHNlc2FtZQ=='
$credentials = $this->basicAuth->obtainCredentials($request);

var_dump($credentials);

//object(Credentials)#1 (2) {
//  ["username":"Credentials":private]=>
//  string(7) "Aladdin"
//  ["password":"Credentials":private]=>
//  string(11) "open sesame"
//}
```
### Add credentials
Add credentials (username, password) to PSR-7 request interface for basic authentication.
```PHP

use Psr\Http\Message\RequestInterface;
use \PFlorek\BasicAuth\BasicAuth;

$credentials = new Credentials('Alladin, 'open sesame');
$request = $this->basicAuth->addCredentials($request, $credentials);

var_dump($request->getHeaderLine('WWW-Authenticate'));

//string(34) "Basic QWxhZGRpbjpvcGVuIHNlc2FtZQ=="
```
### Add challenge
Add challenge with realm to PSR-7 response interface for basic authentication.
```PHP

use Psr\Http\Message\ResponseInterface;
use \PFlorek\BasicAuth\BasicAuth;

$response = $this->basicAuth->addChallenge($response, 'WallyWorld);

var_dump($response->getHeaderLine('WWW-Authenticate'));

//string(24) "Basic realm=\"WallyWorld\""

var_dump($response->getStatusCode());

//int(401)
```


## Installation

Use [Composer] to install the package:

```bash
composer require pflorek/php-basic-auth
```

## Authors

* [Patrick Florek]

## Contribute

Contributions are always welcome!

* Report any bugs or issues on the [issue tracker].
* You can download the sources at the package's [Git repository].

## License

All contents of this package are licensed under the [MIT license].

[Composer]: https://getcomposer.org
[Git repository]: https://github.com/pflorek/php-basic-auth
[issue tracker]: https://github.com/pflorek/php-basic-auth/issues
[MIT license]: LICENSE
[Patrick Florek]: https://github.com/pflorek
[RFC 7617]: https://tools.ietf.org/html/rfc7617
