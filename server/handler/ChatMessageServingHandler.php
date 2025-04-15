<?php

namespace Server\handler;

use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Server\data\ChatRoom;
use Server\data\User;

class ChatMessageServingHandler
{

    private \SplObjectStorage $clients;
    private array $chatRooms;
    private int $chatLimit;

    public function __construct(int $chatLimit)
    {
        $this->clients = new \SplObjectStorage();
        $this->chatRooms = [];
        $this->chatLimit = $chatLimit;
    }

    // 클라이언트 연결 처리.
    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
    }

    // 채팅방 생성
    public function createChatRoom(ConnectionInterface $conn, ChatRoom $chatRoom, User $user) {

        // 이미 같은 uuid를 통한 채팅방이 있으면, 오류 발생 (API 조작의 경우.)
        if (array_key_exists($chatRoom->getUuid(), $this->chatRooms)) {
            throw new \Exception("이미 존재하는 채팅방입니다.");
        }

        // 현재 커넥션의 유저에게 채팅방을 할당함.
        $this->clients[$conn] = [
            'chatRoom' => $chatRoom,
            'user' => $user
        ];

        $this->chatRooms[$chatRoom->getUuid()] = $chatRoom;
    }

    // 채팅방 입장
    public function enterChatRoom(ConnectionInterface $conn, User $user, string $chatRoomUuid)
    {
        // 이미 있는 채팅방이 아니라면, 오류 발생하기.
        if (!array_key_exists($chatRoomUuid, $this->chatRooms)) {
            throw new \Exception("존재하지 않는 채팅방입니다.");
        }

        $chatRoom = $this->chatRooms[$chatRoomUuid];

        // 인원수 초과 (정원 4명)
        if ($chatRoom->getClients()->count() >= $this->chatLimit) {
            throw new \Exception("정원이 초과된 채팅방입니다.");
        }

        // 채팅방 입장하기
        $chatRoom->addClient($conn, $user);
        $this->clients[$conn] = [
            'chatRoom' => $chatRoom,
            'user' => $user
        ];

    }

    // 채팅방에 메세지 보내기
    public function sendChatMessage(ConnectionInterface $conn, $message) {

        // 소속된 채팅방이 있는가?
        if (array_key_exists('chatRoom', $this->clients[$conn])) {
            throw new \Exception("채팅방에 입장하지 않은 클라이언트 입니다.");
        }

        // 채팅방 가져오기.
        $user = $this->clients[$conn]['user'];
        $chatRoom = $this->clients[$conn]['chatRoom'];

        $messageWithRoomInfo = [
            'message' => $message,
            'from' => $user->nickname,
            'nicknames' => $chatRoom->getNicknames()
        ];

        // 메세지 보내기.
        $chatRoom->send($conn, $messageWithRoomInfo);
    }

    // 채팅방을 나갔을 때.
    public function onClose(ConnectionInterface $conn)
    {
        // 클라이언트가 속한 채팅방 가져오기.
        $chatRoom = $this->clients[$conn]['chatRoom'];

        // 채팅방에서 클라이언트 제거.
        $chatRoom->removeClient($conn);

        // 클라이언트 제거.
        $this->clients->detach($conn);
    }

}

?>