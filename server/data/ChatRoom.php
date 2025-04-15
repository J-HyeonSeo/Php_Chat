<?php

namespace Server\data;

use Ratchet\ConnectionInterface;

class ChatRoom
{
    private string $uuid;
    private string $title;
    private \SplObjectStorage $clients;

    public function __construct(string $uuid, string $title, ConnectionInterface $conn)
    {
        $this->uuid = $uuid;
        $this->title = $title;
        $this->clients = new \SplObjectStorage();
        $this->clients->attach($conn);
    }

    /* 데이터 가져오기 */
    public function getUuid()
    {
        return $this->uuid;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getClientsCount() {
        return $this->clients->count();
    }

    public function getNicknames(): array {
        $nicknames = [];

        foreach ($this->clients as $client) {
            array_push($nicknames, $this->clients[$client]);
        }

        return $nicknames;
    }

    /* 데이터 조작하기 */
    public function sendWithMe(string $message) {
        foreach ($this->clients as $client) {
            $client->send($message);
        }
    }

    public function sendWithoutMe(ConnectionInterface $fromConn, string $message)
    {
        foreach ($this->clients as $client) {
            if ($fromConn !== $client) {
                $client->send($message);
            }
        }
    }

    public function addClient($conn, User $user) {
        $this->clients->attach($conn);
        $this->clients[$conn] = $user->getNickname();
    }

    public function removeClient($conn) {
        $this->clients->detach($conn);
    }

}

?>