<?php

namespace Server\handler;

use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Ratchet\WebSocket\MessageComponentInterface;

class MainChatHandler implements MessageComponentInterface
{

    private $chatRoomServingHandler;
    private $chatMessageServingHandler;

    public function __construct()
    {
        $this->chatRoomServingHandler = new ChatRoomServingHandler();
        $this->chatMessageServingHandler = new ChatMessageServingHandler();
    }

    // 신규 클라이언트의 연결 요청이 들어왔을때
    public function onOpen(ConnectionInterface $conn)
    {
        $path = $conn->httpRequest->getUri()->getPath();

        if ($path === '/chat/room') {
            // TODO => 채팅방목록 불러오는 코드 필요..!
            $this->chatRoomServingHandler->onOpen($conn, []);
        } else if (preg_match('#/chat/message/([a-zA-Z0-9\-]{32})#', $path, $matches)) {
            $uuid = $matches[1];
            $this->chatMessageServingHandler->onOpen($conn, $uuid);
        } else {
            // 매칭되는 라우팅이 없으므로, 커넥션 종료 처리.
            $conn->close();
        }

    }

    // WebSocket을 통해 Send메세지를 보냈을때
    public function onMessage(ConnectionInterface $conn, MessageInterface $msg)
    {

    }

    // WebSocket을 연결이 끊어졌을 때
    public function onClose(ConnectionInterface $conn)
    {

    }


    function onError(ConnectionInterface $conn, \Exception $e)
    {

    }
}

?>