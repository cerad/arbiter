<?php
namespace Cerad\Tournament\ProjectOfficial\Register;

use Psr\Http\Message\ServerRequestInterface as Request;

class RegisterForm
{
  protected $project;

  protected $valid  = false;
  protected $posted = false;

  public function setData($data)
  {
    $this->data = $data;
  }
  public function getData()
  {
    return $this->data;
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

  }
  public function render()
  {
    return <<<EOT
<form action="/register" method="POST" enctype="application/x-www-form-urlencoded">
  <input type="submit" name="register" value="Register"/>
</form>
EOT;
  }
}