<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

class Publisher 
{
	public function publish($book, $queue) {
		$host = 'eagle-01.rmq.cloudamqp.com'; 
		$user = 'qmftefgy'; 
		$pass = 'WsT0_0QNnIuMKHotuqG01uNE8RUNi6mS'; 
		$port = '5672'; 
		$vhost = 'qmftefgy';
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
		    'test' => 'hHello world'
		]);

		$message = new AMQPMessage($messageBody, array('content_type' => 'application/json', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
		$channel->basic_publish($message, $exchange);

		$channel->close();
		$connection->close();
	}
}

