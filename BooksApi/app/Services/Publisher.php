<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

class Publisher 
{
	public function publish($book, $queue) {
		$host = 'crocodile-01.rmq.cloudamqp.com'; 
		$user = 'vwoouckh'; 
		$pass = 'ThefGOzsd-mHLs5ra_R8Xqrm2bhZrF5v'; 
		$port = '5672'; 
		$vhost = 'vwoouckh';
		$exchange = $queue;
		$connection = new AMQPStreamConnection($host, $port, $user, $pass, $vhost);
		$channel = $connection->channel();


		$channel->queue_declare($queue, false, true, false, false);

		$channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);
		$channel->queue_bind($queue, $exchange);


		$messageBody = json_encode([
		    // 'title' => $book->title,
		    // 'description' => $book->description,
		    // 'price' => $book->price,
		    'queue' => $queue
		]);

		$message = new AMQPMessage($messageBody, array('content_type' => 'application/json', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
		$channel->basic_publish($message, $exchange);

		$channel->close();
		$connection->close();
	}
}

