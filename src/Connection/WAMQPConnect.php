<?php

namespace Waraqa\Connection;

use PhpAmqpLib\Connection\AMQPSSLConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class WAMQPConnect implements ConnectInterface
{    
    /**
     * connection_string
     *
     * @var string
     */
    public $connection_string;    
    /**
     * exchange
     *
     * @var string
     */
    public $exchange;    
    /**
     * queue
     *
     * @var string
     */
    public $queue;    
    /**
     * cert_path
     *
     * @var string
     */
    public $cert_path;
   
    /**
     * The connection is established in the __construct.  
     *
     * @param  mixed $connection_string
     * @param  mixed $exchange
     * @param  mixed $cert_path
     * @param  mixed $queue
     * @return void
     */
    public function __construct(String $connection_string, String $exchange, String $cert_path = '/etc/ssl/certs', String $queue = null)
    {
        $this->connection_string = $connection_string;
        $this->exchange = $this->queue = $exchange;
        if($queue !== null)
            $this->queue = $queue;
        $this->cert_path = $cert_path;
    }
    
    /**
     * connect to AMQP cloud
     *
     * @return object
     */
    public function connect()
    {
        $url = parse_url($this->connection_string);
        $vhost = substr($url['path'], 1);

        if ($url['scheme'] === "amqps") {
            $ssl_opts = array(
                'capath' => $this->cert_path
            );
            $this->connection = new AMQPSSLConnection($url['host'], 5671, $url['user'], $url['pass'], $vhost, $ssl_opts);
        } else {
            $this->connection = new AMQPStreamConnection($url['host'], 5672, $url['user'], $url['pass'], $vhost);
        }

        return $this;
    }
}
