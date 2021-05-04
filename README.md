# Getting Started

Install the package using composer and look below for documentation on how to use the package.
````
composer require krve/inmobile
````

### Sending a message
```php
use Krve\Inmobile\Gateway;
use Krve\Inmobile\Message;

$gateway = new Gateway('api-key');

$gateway->send(
    Message::create('Hello World')
        ->from('MyCompany')
        ->to(4500000000)
);
```

### Getting the response from Inmobile
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

## TODO
- [ ] Add proper error handling and error messages
- [x] Add proper response classes
