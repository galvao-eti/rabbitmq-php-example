#! /usr/bin/env php
<?php

declare(strict_types = 1);
require __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\{
    Exception\AMQPTimeoutException,
    Exception\AMQPChannelClosedException,
    Exception\AMQPConnectionClosedException,
    Exception\AMQPConnectionBlockedException,
    Message\AMQPMessage,
};

require __DIR__ . '/require/rabbitmqConnect.php';

/**
 * Exchange declare documentation (copied here for ease of use)
 * @see https://php-amqplib.github.io/php-amqplib/classes/PhpAmqpLib-Channel-AMQPChannel.html#method_exchange_declare
 *
 * @param string $exchange - Exchange name
 * @param string $type - Exchange type
 * @param bool $passive
 * @param bool $durable - If the queue is durable, meaning, if it survives a broker restart
 * @param bool $autoDelete
 * @param bool $internal -
 * @param bool $noWait
 * @param array<string|int, mixed> AMQPTable $arguments -
 * @param int|null $ticket
 *
 * @throws AMQPTimeoutException
 *
 * @return mixed|null
 */

try {
    $channel->exchange_declare('info_exchange', 'direct', false, false, false);
} catch(AMQPTimeoutException $e) {
    echo 'Error: ' . $e->getMessage();
    exit(1);
}


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

/**
 * Basic publish documentation (copied here for ease of use)
 * @see https://php-amqplib.github.io/php-amqplib/classes/PhpAmqpLib-Channel-AMQPChannel.html#method_basic_publish
 *
 * @param AMQPMessage $message - The message
 * @param string $exchange - Exchange type
 * @param string $routingKey - Routing Key
 * @param bool $mandatory -
 * @param bool $immediate
 * @param int|null $ticket
 *
 * @throws AMQPChannelClosedException|AMQPConnectionClosedException|AMQPConnectionBlockedException
 *
 * @return mixed
 */

try {
    $channel->basic_publish($message, 'info_exchange', 'sandbox:info');
} catch (AMQPChannelClosedException|AMQPConnectionClosedException|AMQPConnectionBlockedException $e) {
    echo 'Error: ' . $e->getMessage();
    exit(1);
}

$channel->close();
$connection->close();
