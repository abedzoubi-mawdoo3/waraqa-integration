<?php

namespace Waraqa;

use PhpAmqpLib\Message\AMQPMessage;
use Waraqa\Connection\WAMQPConnect;

class Producer
{    
    /**
     * produce message to AMQP cloud to notify the client to pull the article
     *
     * @param  mixed $WConnection
     * @param  mixed $job
     * @param  mixed $args
     * @return void
     */
    public function produce(WAMQPConnect $WConnection, $job = '', array $args = array())
    {
        $connection = $WConnection->connection;
        $channel = $connection->channel();
        $channel->queue_declare($WConnection->queue, false, true, false, false);
        $channel->exchange_declare($WConnection->exchange, 'direct', true, true, false);
        $channel->queue_bind($WConnection->queue, $WConnection->exchange);
        $messageBody = json_encode(array(
            'job' => $job,
            'args' => $args
        ));
        $message = new AMQPMessage(
            $messageBody,
            array(
                'content_type' => 'text/plain',
                'delivery_mode' => 2
            )
        );
        $channel->basic_publish($message, $WConnection->exchange);
        $channel->close();
        $connection->close();
    }
}
