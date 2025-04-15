<?php

namespace Server\handler;

use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Ratchet\WebSocket\MessageComponentInterface;
use Server\data\ChatRoom;
use Server\data\User;

class MainChatHandler implements MessageComponentInterface
{

    private $chatRoomServingHandler;
    private $chatMessageServingHandler;

    public function __construct()
    {
        $this->chatRoomServingHandler = new ChatRoomServingHandler();
        $this->chatMessageServingHandler = new ChatMessageServingHandler(4);
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

        // 메세지를 보내는 요청이 아니라면, 조작된 요청이므로, 차단.
        if ($conn->httpRequest->getUri()->getPath() !== "/chat/message") {
            return;
        }

        // 데이터 추출.
        $jsonData = json_decode($msg->getPayload(), true);

        // 타입 추출.
        $type = $jsonData['type'];

        switch ($type) {
            case 'CREATE': // 방만들기

                // 요청시 필요한 JSON 데이터.
                $uuid = $jsonData['uuid'];
                $title = $jsonData['title'];
                $nickname = $jsonData['nickname'];

                $chatRoom = new ChatRoom($uuid, $title, $conn);
                $user = new User($nickname);

                $this->chatMessageServingHandler->createChatRoom($conn, $chatRoom, $user);

                break;
            case 'JOIN':   // 방입장하기

                // 요청시 필요한 JSON 데이터.
                $uuid = $jsonData['uuid'];
                $nickname = $jsonData['nickname'];

                $user = new User($nickname);

                $this->chatMessageServingHandler->enterChatRoom($conn, $user, $uuid);

                break;
            case 'SEND_MESSAGE': // 메세지보내기

                // 요청시 필요한 JSON 데이터.
                $message = $jsonData['message'];

                $this->chatMessageServingHandler->sendChatMessage($conn, $message);

                break;
        }

    }

    // WebSocket을 연결이 끊어졌을 때
    public function onClose(ConnectionInterface $conn)
    {
        // 채팅방 핸들러, 채팅메세지 핸들러의 클라이언트를 조사해서, 목록을 제거해야함.
        $this->chatRoomServingHandler->onClose($conn);
        $this->chatMessageServingHandler->onClose($conn);
    }


    // 웹소켓 오류 발생시
    function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "서버에 오류가 발생하였습니다. : {$e->getMessage()}\n";
    }
}

?>