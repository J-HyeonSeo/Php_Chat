<?php

use Ratchet\ConnectionInterface;

class ChatRoomServingHandler {

  private SplObjectStorage $clients;

  public function __construct()
  {
    $this->clients = new SplObjectStorage;
  }

  // 커넥션이 연결되었다면, 채팅방 목록을 제공해주어야 함.
  public function onOpen(ConnectionInterface $conn, array $chatRoomList) {
    $this->clients->attach($conn);
    $this->onMessage($chatRoomList);
  }

  // 클라이언트에서 채팅방을 신규로 생성하면, 이벤트를 보낼거임. 연결된 clients들에게 변경사항 응답.
  public function onMessage(array $chatRoomList) {
    foreach ($this->clients as $client) {
      $client->send($chatRoomList);
    }
  }

  // 연결이 종료된 웹소켓이 감지되면, 채팅방 정보 전달 대상에서 제외시킴.
  public function onClose(ConnectionInterface $conn) {
    $this->clients->detach($conn);
  }

}

?>