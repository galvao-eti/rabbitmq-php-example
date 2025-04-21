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

try {
    $channel->exchange_declare('info_exchange', 'direct', false, false, false);
} catch(AMQPTimeoutException $e) {
    echo 'Error: ' . $e->getMessage();
    exit(1);
}

list($queueName, ,) = $channel->queue_declare('sandbox', false, true, false, false, false);

/**
 * Queue bind documentation (copied here for ease of use)
 * @see https://php-amqplib.github.io/php-amqplib/classes/PhpAmqpLib-Channel-AMQPChannel.html#method_queue_bind
 *
 * @param string $queue - Queue namee
 * @param string $exchange - Exchange name
 * @param string $routingKey - Routing Key
 * @param bool $noWait
 * @param array<string|int, mixed> AMQPTable $arguments -
 * @param int|null $ticket
 *
 * @throws AMQPTimeoutException
 *
 * @return mixed|null
 */

try {
    $channel->queue_bind($queueName, 'info_exchange', 'sandbox:info');
} catch(AMQPTimeoutException $e) {
    echo 'Error: ' . $e->getMessage();
    exit(1);
}

/**
 * Basic consume documentation (copied here for ease of use)
 * @see https://php-amqplib.github.io/php-amqplib/classes/PhpAmqpLib-Channel-AMQPChannel.html#method_basic_consume
 *
 * @param string $queue - Queue namee
 * @param string $consumerTag
 * @param string $routingKey - Routing Key
 * @param bool $no_local -
 * @param bool $no_ack -
 * @param bool $exclusive
 * @param bool $noWait
 * @param callable|null $callback
 * @param int|null $ticket
 * @param array<string|int, mixed> AMQPTable $arguments -
 *
 * @throws AMQPTimeoutException|InvalidArgumentException
 *
 * @return string
 */

try {
    $channel->basic_consume('sandbox', '', false, true, false, false, function ($message) {
        echo 'Received ' . $message->getBody() . PHP_EOL;
    });
} catch(AMQPTimeoutException|InvalidArgumentException $e) {
    echo 'Error: ' . $e->getMessage();
    exit(1);
}

try {
    $channel->consume();
} catch(ErrorException $e) {
    echo 'Error: ' . $e->getMessage();
    exit(1);
}

$channel->close();
$connection->close();
