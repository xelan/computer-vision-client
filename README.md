# Computer Vision API Client

This library provides a simple client for Microsoft's [Computer Vision API](https://docs.microsoft.com/en-us/azure/cognitive-services/computer-vision/)
using the [Guzzle PHP HTTP client](http://guzzlephp.org/).

## Requirements

* PHP 5.5 or higher
* PHP CURL extension

## Installation (Composer)

This library is published as a [Composer package](https://packagist.org/packages/andaris/computer-vision-client), so Composer is the recommended way to install it.

Add `computer-vision-client` to your application's `composer.json` file:
```json
{
    "require": {
        "andaris/computer-vision-client": "0.1.0"
    }
}
```

Navigate to your project root and run:
```
$ composer install
```

Make sure that the Composer autoloader is in your project's bootstrap.

## Usage

```php
use Andaris\ComputerVision\Client;

$imageData = file_get_contents('/path/to/image.jpg');
$client = new Client('14758f1afd44c09b7992073ccf00b43d'); // Insert your API key
$result = $client->analyze($imageData, [Client::FEATURE_CATEGORIES, Client::FEATURE_TAGS]);
```

## Testing

The PHPUnit test suite can be run via `vendor/bin/phpunit`.
