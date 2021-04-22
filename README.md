# Getting Started

### Sending a message
```php
use Krve\Inmobile\InmobileMessageSender;
use Krve\Inmobile\InmobileMessage;

$sender = new InmobileMessageSender('api-key', 'Default Sender Name');

$message = (new InmobileMessage())
    ->from('MyCompany')
    ->setMessage('Hello World')
    ->to(4500000000);

$recipients = $sender->send($message);
```

### Specifiying extra parameters

```php
use Krve\Inmobile\InmobileMessage;

$message = (new InmobileMessage())
    ->from('MyCompany')
    ->setMessage('Hello World')
    ->to(4500000000)
    ->setEncoding(InmobileMessage::ENCODING_UTF8)
    ->flash()
    ->setExpireInSeconds(10)
    ->setSendTime('2012-01-01 12:00:00');
```

### Setting a default sender
```php
use Krve\Inmobile\InmobileMessageSender;
use Krve\Inmobile\InmobileMessage;

$sender = new InmobileMessageSender('api-key', 'MyDefault');

$message = (new InmobileMessage())
    ->setMessage('Hello World')
    ->to(4500000000);

$recipients = $sender->send($message);
```

## TODO
- [ ] Add proper error handling and error messages
- [ ] Add support for message status with push messages
- [ ] Rewrite InmobileSender to make use of an API class (this is needed to support the rest of their API)
