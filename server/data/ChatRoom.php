<?php

class ChatRoom {
  private string $uuid;
  private string $title;
  private SplObjectStorage $clients;

  public function __construct(string $uuid, string $title) {
    $this->uuid = $uuid;
    $this->title = $title;
    $this->clients = new SplObjectStorage;
  }

  public function getUuid() {
    return $this->uuid;
  }

  public function getTitle() {
    return $this->title;
  }

  public function getClients() {
    return $this->clients;
  }

}

?>