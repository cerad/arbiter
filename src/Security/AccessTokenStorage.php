<?php
namespace Cerad\Security;

class AccessTokenStorage
{
  protected $token;

  public function get()
  {
    return $this->token;
  }
  public function set($token)
  {
    $this->token = $token;
  }
}