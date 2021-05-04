# Inmobile SMS Gateway

This is a PHP package for sending text messages using [Inmobile](https://www.inmobile.com/).
Inmobile is a text message gateway.

Read more about their API here: https://docs.inmobile.dk/

## Getting Started

Install the package using composer and look below for documentation on how to use the package.
````
composer require krve/inmobile
````

### Sending a message
```php
use Krve\Inmobile\Gateway;
use Krve\Inmobile\Message;

$gateway = new Gateway('api-key');

$response = $gateway->send(
    Message::create('Hello World')
        ->from('MyCompany')
        ->to(4500000000)
);

$response->toArray();

/**
 * [
 *     ['msisdn' => '4500000000', 'id' => 'id-1']
 * ] 
 */
```

### Setting a callback URL
```php
use Krve\Inmobile\Gateway;
use Krve\Inmobile\Message;

$gateway = new Gateway('api-key');

$response = $gateway->send(
    Message::create('Hello World')
        ->from('MyCompany')
        ->to(4500000000),
    'https://example.com/callback'
);
```

### Error handling
If you make a request to Inmobile, and the request fails, it throws an `GatewayErrorException`.
This exception contains the Inmobile error code, and an exception message depending on the code.

```php
// Response code is -11

$exception->getMessage(); // "Inmobile error: OverchargeDonationLimitExceeded"
$exception->getInmobileErrorCode(); // -11 
```

If the status code from Inmobile is not found, it has the following exception message
```php
$exception->getMessage(); // "ERROR: unknown response from inmobile. Response code was {statusCode}"
```
