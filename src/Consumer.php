<?php

namespace Waraqa;

use Waraqa\Connection\WAMQPConnect;

class Consumer
{
    /**
     * consume
     *
     * @param  mixed $WConnection
     * @param  mixed $callback
     * @param  bool $check
     * @return void
     */
    public function consume(WAMQPConnect $WConnection, $callback, $check=true)
    {
        $connection = $WConnection->connection;
        $channel = $connection->channel();

        $channel->queue_declare($WConnection->queue, false, true, false, false);
        $channel->basic_qos(null, 1, null);
        $channel->exchange_declare($WConnection->exchange, 'direct', false, true, false);
        $channel->queue_bind($WConnection->queue, $WConnection->exchange);

        $channel->basic_consume($WConnection->queue, 'consumer_tag', false, false, false, false, $callback);

        
        if($check) {
            register_shutdown_function(array($this, 'onShutdown'), $WConnection);
            while (count($channel->callbacks) > 0) {
                $channel->wait();
            }
        }
    }

    /**
     * onShutdown
     *
     * @param  mixed $WConnection
     * @return void
     */
    public function onShutdown(WAMQPConnect $WConnection)
    {
        $connection = $WConnection->connection;
        $channel = $connection->channel();
        $channel->close();
        $connection->close();
    }
}
