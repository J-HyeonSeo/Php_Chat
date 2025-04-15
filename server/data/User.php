<?php

namespace Server\data;
class User {
  private string $nickname;

  public function __construct(string $nickname) {
    $this->nickname = $nickname;
  }

  public function getNickname(): string {
      return $this->nickname;
  }
}

?>