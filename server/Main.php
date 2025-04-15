<?php

require __DIR__ . '/../vendor/autoload.php';

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

$chatServer = new MainChatHandler();

$server = IoServer::factory(
  new HttpServer(
    new WsServer($chatServer)
  ),
  7778
);

$server->run();

?>