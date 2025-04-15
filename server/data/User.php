<?php

class User {
  private string $nickname;

  public function __construct(string $nickname) {
    $this->nickname = $nickname;
  }
}

?>