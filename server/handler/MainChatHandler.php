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
            $chatRoomsInfo = $this->chatMessageServingHandler->getChatRoomsInfo();
            $this->chatRoomServingHandler->onOpen($conn, $chatRoomsInfo);
        } else if ($path === '/chat/message') {
            $this->chatMessageServingHandler->onOpen($conn);
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
            throw new \Exception("허용되지 않은 요청을 보냈습니다.");
        }

        // 데이터 추출.
        $jsonData = json_decode($msg->getPayload(), true);

        // 타입 데이터 검증.
        if (!array_key_exists('type', $jsonData)) {
            throw new \Exception("메세지 유형을 입력해주세요.");
        }

        // 타입 추출.
        $type = $jsonData['type'];
        echo $type . PHP_EOL;

        switch ($type) {
            case 'CREATE': // 방만들기

                if (!array_key_exists('uuid', $jsonData)) {
                    throw new \Exception("채팅방 uuid를 입력해주세요.");
                }

                if (!array_key_exists('title', $jsonData)) {
                    throw new \Exception("채팅방 제목을 입력해주세요.");
                }

                if (!array_key_exists('nickname', $jsonData)) {
                    throw new \Exception("닉네임을 입력해주세요.");
                }

                // 요청시 필요한 JSON 데이터.
                $uuid = $jsonData['uuid'];
                $title = $jsonData['title'];
                $nickname = $jsonData['nickname'];

                $chatRoom = new ChatRoom($uuid, $title, $conn);
                $user = new User($nickname);

                $this->chatMessageServingHandler->createChatRoom($conn, $chatRoom, $user);

                // 신규로 방을 생성했으므로, Room대기 화면에 있는 클라이언트에게 정보 전달.
                $this->chatRoomServingHandler->onMessage($this->chatMessageServingHandler->getChatRoomsInfo());

                break;
            case 'ENTER':   // 방입장하기

                if (!array_key_exists('uuid', $jsonData)) {
                    throw new \Exception("채팅방 uuid를 입력해주세요.");
                }

                if (!array_key_exists('nickname', $jsonData)) {
                    throw new \Exception("닉네임을 입력해주세요.");
                }

                // 요청시 필요한 JSON 데이터.
                $uuid = $jsonData['uuid'];
                $nickname = $jsonData['nickname'];

                $user = new User($nickname);

                $this->chatMessageServingHandler->enterChatRoom($conn, $user, $uuid);

                // 인원에 대한 변동으로 방정보 내용 재전송.
                $this->chatRoomServingHandler->onMessage($this->chatMessageServingHandler->getChatRoomsInfo());

                break;
            case 'SEND_MESSAGE': // 메세지보내기

                if (!array_key_exists('message', $jsonData)) {
                    throw new \Exception("메세지를 입력해주세요.");
                }

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

        // 인원에 대한 변동으로 방정보 내용 재전송.
        $this->chatRoomServingHandler->onMessage($this->chatMessageServingHandler->getChatRoomsInfo());
    }


    // 웹소켓 오류 발생시
    function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "서버에 오류가 발생하였습니다. : {$e->getMessage()}\n";

        $conn->send(
            json_encode([
                'type' => 'ERROR',
                'error_message' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE)
        );
    }
}

?>