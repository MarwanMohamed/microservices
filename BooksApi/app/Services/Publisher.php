<?php

// require dirname(__DIR__). '/vendor/autoload.php';

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

class Publisher 
{

	public function publish($book) {
		$host = 'eagle-01.rmq.cloudamqp.com'; 
		$user = 'qmftefgy'; 
		$pass = 'WsT0_0QNnIuMKHotuqG01uNE8RUNi6mS'; 
		$port = '5672'; 
		$vhost = 'qmftefgy';
		$exchange = 'subscribers';
		$queue = 'publish_book';
		$connection = new AMQPStreamConnection($host, $port, $user, $pass, $vhost);
		$channel = $connection->channel();


		$channel->queue_declare($queue, false, true, false, false);

		$channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);
		$channel->queue_bind($queue, $exchange);


		$messageBody = json_encode([
		    'name' => $book->title,
		    'email' => $book->description,
		    'price' => $book->price,
		]);

		$message = new AMQPMessage($messageBody, array('content_type' => 'application/json', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
		$channel->basic_publish($message, $exchange);

		echo "finished publishing to queue " . $queue . PHP_EOL;

		$channel->close();
		$connection->close();
	}
}

