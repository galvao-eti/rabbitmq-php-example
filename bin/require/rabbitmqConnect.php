#! /usr/bin/env php
<?php

declare(strict_types = 1);

use Dotenv\Dotenv;

use PhpAmqpLib\{
    Connection\AMQPStreamConnection,
    Exception\AMQPOutOfBoundsException,
    Exception\AMQPRuntimeException,
    Exception\AMQPTimeoutException,
    Exception\AMQPConnectionClosedException,
};

$env = DotEnv::createImmutable(__DIR__ . '/../..');
$env->load();

/**
 * AMQPStreamConnection documentation (copied here for ease of use)
 * @see https://php-amqplib.github.io/php-amqplib/classes/PhpAmqpLib-Connection-AMQPStreamConnection.html#method___construct
 *
 * @param string $host - Host address
 * @param int $port - Connection port
 * @param string $user
 * @param string $password
 * @param string $vhost
 * @param bool $insist
 * @param string $loginMethod
 * @param null $loginResponse
 * @param string $locale
 * @param float connectionTimeout
 * @param float readWriteTimeout
 * @param resource|array<string|int, mixed>|null $context
 * @param bool $keepAlive
 * @param int $heartBeat
 * @param float $channelRpcTimeout
 * @param string|AMQPConnectionConfig|null $sslProtocol
 * @param AMQPConnectionConfig|null $config
 *
 * @throws Exception
 *
 * @return mixed
 */

try {
    $connection = new AMQPStreamConnection(
        $_ENV['AMQP_HOST'],
        $_ENV['AMQP_PORT'],
        $_ENV['AMQP_USER'],
        $_ENV['AMQP_PASS'],
        $_ENV['AMQP_VHOST'],
    );
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage();
    exit(1);
}

/**
 * Channel documentation (copied here for ease of use)
 * @see https://php-amqplib.github.io/php-amqplib/classes/PhpAmqpLib-Connection-AMQPStreamConnection.html#method_channel
 *
 * @param int|null $channelId
 *
 * @throws AMQPOutOfBoundsException|AMQPRuntimeException|AMQPTimeoutException|AMQPConnectionClosedException
 *
 * @return AMQPChannel
 */

try {
    $channel = $connection->channel();
} catch(AMQPOutOfBoundsException|AMQPRuntimeException|AMQPTimeoutException|AMQPConnectionClosedException $e) {
    echo 'Error: ' . $e->getMessage();
    exit(1);
}
