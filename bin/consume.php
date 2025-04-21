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

$channel->consume();

$channel->close();
$connection->close();
