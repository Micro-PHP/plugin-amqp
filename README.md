# Micro Framework AMQP Component

Micro Framework plugin. AMQP protocol support based on [php-amqplib/php-amqplib](https://github.com/php-amqplib/php-amqplib) library.

## Installation

Use the package manager [Composer](https://getcomposer.org/) to install.

```bash
composer require micro/plugin-amqp
```

## Basic Configure

Append plugin to ./etc/plugins.php

```php
<?php 

return [
/*...... Other plugin list...*/
        Micro\Plugin\Amqp\AmqpPlugin::class,
];

```

## Basic Usage


#### Create consumer processor

```php

use Micro\Plugin\Amqp\Business\Consumer\ConsumerProcessorInterface;

class ConsumerProcessor implements ConsumerProcessorInterface {
    public function receive(MessageReceivedInterface $message): bool
     {   
         echo "Received message: ID: " . $message->getId() . "Content: " . $message->content();
         // Message success consumed
         $message->ack();
         
         return true;
     }
}

```

#### Register consumer

```php

$amqpFacade = $container->get(AmqpFacadeInterface::class);
$amqpFacade->registerConsumerProcessor(new ConsumerProcessor());

```

#### Run consumer
```bash
$ bin/console micro:amqp:consume
```

#### Send message
```php
$id = 'Some unique key';
$message = new Message($id, 'Book created !');
$container->get(AmqpFacadeInterface::class)->publish($message);
```

## Other docs

 * ### [Full configuration list](docs/Configuration.md) 




## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](LICENSE)
