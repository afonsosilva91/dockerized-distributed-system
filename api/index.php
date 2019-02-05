<?php

require 'vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$app = new Slim\App();

$app->post('/auth', function ($request, $response, $args) {
    $data = $request->getQueryParams();

    try {
        $connection = new AMQPStreamConnection('consumer', 5672, 'guest', 'guest');
        $channel = $connection->channel();
    
        $channel->queue_declare('api.auth', false, false, false, false);

        $msg = new AMQPMessage(json_encode([
            'username' => $data['username'] ?? null,
            'password' => $data['password'] ?? null
        ]));

        $channel->basic_publish($msg, '', 'api.auth');

        $channel->close();
        $connection->close();
    } catch (\Exception $ex) {
        return $response->withJson([
            'status' => false,
            'message' => $ex->getMessage()
        ]);
    }

    return $response->withJson([
        'status' => true
    ]);
});

$app->post('/recover-password', function ($request, $response, $args) {
    $data = $request->getQueryParams();
    $email = $data['username'] ?? null;

    try {
        $connection = new AMQPStreamConnection('consumer', 5672, 'guest', 'guest');
        $channel = $connection->channel();
    
        $channel->queue_declare('api.recover.password', false, false, false, false);

        $msg = new AMQPMessage(json_encode([
            'email' => $email
        ]));

        $channel->basic_publish($msg, '', 'api.recover.password');

        $channel->close();
        $connection->close();
    } catch (\Exception $ex) {
        return $response->withJson([
            'status' => false,
            'message' => $ex->getMessage()
        ]);
    }

    return $response->withJson([
        'status' => true,
        'email' => $email
    ]);
});

$app->run();
