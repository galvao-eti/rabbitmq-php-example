#! /usr/bin/env php
<?php

declare(strict_types = 1);
require __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\{
    Message\AMQPMessage,
};

require __DIR__ . '/require/rabbitmqConnect.php';

/**
 * Queue declare documentation (copied here for ease of use)
 * @see https://php-amqplib.github.io/php-amqplib/classes/PhpAmqpLib-Channel-AMQPChannel.html#method_queue_declare
 *
 * @param string $queue - Queue name
 * @param bool $passive
 * @param bool $durable - If the queue is durable, meaning, if it survives a broker restart
 * @param bool $exclusive - If the queue should be exclusive to this connection
 * @param bool $autoDelete - If the queue should delete itself when the client disconnects
 * @param bool $noWait
 * @param array<string|int, mixed> AMQPTable $arguments -
 * @param int|null $ticket
 *
 * @throws AMQPTimeoutException
 *
 * @return array<string|int, mixed>|null
 */

try {
    $channel->queue_declare('sandbox', false, true, false, false);
} catch (AMQPTimeoutException $e) {
    echo 'Error: ' . $e->getMessage();
    exit(1);
}

$message = new AMQPMessage('This is a test.');
$channel->basic_publish($message, '', 'sandbox');

$channel->close();
$connection->close();
