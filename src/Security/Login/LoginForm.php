<?php
namespace Cerad\Security\Login;

use Psr\Http\Message\ServerRequestInterface as Request;

class LoginForm
{
  protected $username;
  protected $password;
  protected $rememberMe;

  protected $valid  = false;
  protected $posted = false;

  public function getData()
  {
    return [
      'username'    => $this->username,
      'password'    => $this->password,
      'remember_me' => $this->rememberMe,
    ];
  }
  public function isValid() {
    return $this->valid;
  }
  public function handleRequest(Request $request)
  {
    if ($request->getMethod() !== 'POST') {
      return;
    }
    $this->posted = true;
    $this->valid  = true;

    $post = $request->getParsedBody();

    $this->username = $post['username'];
    $this->password = $post['password'];

    //$this->rememberMe = $post['remember_me'];
  }
  public function render()
  {
    return <<<EOT
<form action="/login" method="POST" enctype="application/x-www-form-urlencoded">
  <input type="text"     name="username" size="30" required/><br/>
  <input type="password" name="password" size="30" required/><br/>
  <input type="submit" name="login" value="Log In"/>
</form>
EOT;
  }
}