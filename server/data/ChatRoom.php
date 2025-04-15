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

    public function send(ConnectionInterface $fromConn, string $message)
    {
        foreach ($this->clients as $client) {
            if ($fromConn !== $client) {
                $client->send($message);
            }
        }
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getNicknames(): array {
        $nicknames = [];

        foreach ($this->clients as $client) {
            array_push($nicknames, $this->clients[$client]);
        }

        return $nicknames;
    }

    public function addClient($conn, User $user) {
        $this->clients->attach($conn);
        $this->clients[$conn] = $user;
    }

    public function removeClient($conn) {
        $this->clients->detach($conn);
    }

}

?>