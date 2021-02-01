# Waraqa Intgration




This package an integration SDK for waraqa services, also it connects to cloudamqp.com the RabbitMQ cloud base service.

To install this package:

```
composer require mawdoo3com/waraqa-integration
```

**Consumer Usage**:
```
<?php

use Waraqa\Connection\WAMQPConnect;
use PhpAmqpLib\Message\AMQPMessage;
use Waraqa\Consumer;

class WaraqaIntegration
{

    public function execute()
    {
        $client_id = getenv('CLIENT_ID');//will provided by waraqa admin
        $connection_string = getenv('AQMP_CONNECTION');//will provided by waraqa admin
        $connection_obj = new WAMQPConnect($connection_string, $client_id);
        $connection = $connection_obj->connect();

        $consumer = new Consumer();
        $consumer->consume($connection, [$this, 'process']);
    }

    public function process(AMQPMessage $message)
    {
        //your code goes here, this is the callback function
    }
}
```
---

**Producer Usage**:
```
<?php

use Waraqa\Connection\WAMQPConnect;
use Waraqa\Producer;

class WaraqaIntegration
{

    public function execute()
    {
        $client_id = getenv('CLIENT_ID');//will provided by waraqa admin
        $connection_string = getenv('AQMP_CONNECTION');//will provided by waraqa admin
        $connection_obj = new WAMQPConnect($connection_string, $client_id);
        $connection = $connection_obj->connect();

        $producer = new Producer();
        $producer->produce($connection,'message',[param1,param2,...etc]);
    }
}
```
