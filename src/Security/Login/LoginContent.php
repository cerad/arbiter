<?php
namespace Cerad\Security\Login;

class LoginContent
{
  private $layout;

  public function __construct($layout)
  {
    $this->layout = $layout;
  }
  public function render(LoginForm $form)
  {
    $html = <<<EOT
<h2>User Login</h2>
{$form->render()}
EOT;
    $this->layout->setContent($html);
    return $this->layout->render();
  }
}