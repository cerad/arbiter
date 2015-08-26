<?php
namespace Cerad\Component\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface, \ArrayAccess
{
  protected $username;
  protected $password;
  protected $salt;
  protected $roles = [];

  protected $id;
  protected $email;

  public function __construct(array $params = [])
  {
    foreach($params as $key => $value) {
      $this->offsetSet($key,$value);
    }
  }
  /** =================================
   * Like my arrays
   */
  public function offsetGet($key)
  {
    switch($key) {
      case 'username':
      case 'password':
      case 'salt':
      case 'roles':

      case 'id':
      case 'email':
        return $this->$key;
    };
    throw new \InvalidArgumentException('User::offsetGet ' . $key);
  }
  public function offsetSet($key,$value)
  {
    switch($key) {
      case 'username':
      case 'password':
      case 'salt':
      case 'roles':

      case 'id':
      case 'email':
        return $this->$key = $value;
    };
    throw new \InvalidArgumentException('User::offsetSet ' . $key);
  }
  public function offsetExists($key)
  {
    switch($key) {
      case 'username':
      case 'password':
      case 'salt':
      case 'roles':

      case 'id':
      case 'email':
        return true;
    };
    return false;
  }
  public function offsetUnset($key) { return; }

  /* ===========================================
   * Keep the Symfony user interface for now
   * Todo: Maybe implement AdvancedUserInterface
   */
  public function getUsername() { return $this->username; }
  public function getPassword() { return $this->password; }
  public function getSalt()     { return $this->salt;     }
  public function getRoles()    { return $this->roles;    }

  public function eraseCredentials() {}
}