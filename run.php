<?php
declare(strict_types=1);

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

require __DIR__ . '/vendor/autoload.php';

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new \FrankHouweling\CardsSpeak\Application()
        )
    ),
    3030
);

$server->run();
