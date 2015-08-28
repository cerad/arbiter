<?php
namespace Cerad\Tournament\ProjectOfficial\Register;

class RegisterContent
{
  private $form;
  private $layout;

  public function __construct($layout,RegisterForm $form)
  {
    $this->form   = $form;
    $this->layout = $layout;
  }
  public function render()
  {
    $html = <<<EOT
<h2>Tournament Referee Registration</h2>
{$this->form->render()}
<p>
EOT;
    $this->layout->setContent($html);
    return $this->layout->render();
  }
}