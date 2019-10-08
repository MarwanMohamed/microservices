<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;
use App\Author;

class NotificationsConsumer 
{
	public function consume() 
	{
		$host = 'crocodile-01.rmq.cloudamqp.com'; 
		$user = 'vwoouckh'; 
		$pass = 'ThefGOzsd-mHLs5ra_R8Xqrm2bhZrF5v'; 
		$port = '5672'; 
		$vhost = 'vwoouckh';
		$exchange = 'notification';
		$queue = 'notification';
		$connection = new AMQPStreamConnection($host, $port, $user, $pass, $vhost);
		$channel = $connection->channel();


		$channel->queue_declare($queue, false, true, false, false);

		$channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);
		$channel->queue_bind($queue, $exchange);

		$callback = function($msg) { // here to test the queue working successfully
		   Author::create(['name' => 'test name', 'country' => 'test co' , 'gender' => 'male', 'desc' => $msg->body]);
		};

		$consumerTag = 'local.imac.consumer';
		$channel->basic_consume($queue, $consumerTag, false, true, false, false, $callback);

		function shutdown($channel, $connection)
		{
		    $channel->close();
		    $connection->close();
		}
		// register_shutdown_function('shutdown', $channel, $connection);

		while ($channel ->is_consuming()) {
		    $channel->wait();
		}

	}
		
}


