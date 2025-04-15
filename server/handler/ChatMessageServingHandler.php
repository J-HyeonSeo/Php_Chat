<?php

namespace Server\handler;

use Ratchet\ConnectionInterface;

class ChatMessageServingHandler {

  private \SplObjectStorage $clients;
  private $chatRoomList;

  public function __construct()
  {
    $this->clients = new \SplObjectStorage();
  }

  public function onOpen(ConnectionInterface $conn, string $uuid) {
    
  }

  public function onMessage() {

  }

  public function onClose(ConnectionInterface $conn) {

  }

}

?>