<?php

require_once __DIR__ . '/../vendor/autoload.php';

set_time_limit(0);

$socket = new \HemiFrame\Lib\WebSocket\WebSocket("localhost", 8080);
$socket->setEnableLogging(true);

$socket->on("receive", function (\HemiFrame\Lib\WebSocket\Client $client, $data) use ($socket) {
    foreach ($socket->getClientsByPath($client->getPath()) as $item) {
        /* @var $item \HemiFrame\Lib\WebSocket\Client */
//		if ($item->id != $client->id) {
        $socket->sendData($item, $data);
//		}
    }
});
$socket->on("connect", function (\HemiFrame\Lib\WebSocket\Client $client) {
});

$socket->on("receive", function (\HemiFrame\Lib\WebSocket\Client $client, $data) {
});

$socket->on("send", function (\HemiFrame\Lib\WebSocket\Client $client, $data) {
});

$socket->on("ping", function (\HemiFrame\Lib\WebSocket\Client $client, $data) {
});

$socket->on("pong", function (\HemiFrame\Lib\WebSocket\Client $client, $data) {
});

$socket->on("disconnect", function (\HemiFrame\Lib\WebSocket\Client $client, $statusCode, $reason) {
});

$socket->on("error", function ($socket, $client, $phpError, $errorMessage, $errorCode) {
    var_dump("Error: => " . implode(" => ", $phpError));
});

$socket->startServer();
