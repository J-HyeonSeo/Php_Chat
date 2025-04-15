<?php

use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Ratchet\WebSocket\MessageComponentInterface;

class MainChatHandler implements MessageComponentInterface {

  private $chatRoomServingHandler;
  private $chatMessageServingHandler;

  public function __construct()
  {
    $this->chatRoomServingHandler = new \Server\handler\ChatRoomServingHandler();
    $this->chatMessageServingHandler = new ChatMessageServingHandler();
  }

  // 신규 클라이언트의 연결 요청이 들어왔을때
  public function onOpen(ConnectionInterface $conn)
  {
    $path = $conn->httpRequest->getUri()->getPath();
    print_r($path);
  }

  // WebSocket을 통해 Send메세지를 보냈을때
  public function onMessage(ConnectionInterface $conn, MessageInterface $msg)
  {
    
  }

  // WebSocket을 연결이 끊어졌을 때
  public function onClose(ConnectionInterface $conn)
  {
    
  }

  public function onError(ConnectionInterface $conn, Exception $e)
  {
    
  }

}

?>