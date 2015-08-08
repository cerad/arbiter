<?php
namespace Cerad\Component\Arbiter\Avail;

class AvailContent
{
  private $layout;

  public function __construct($layout)
  {
    $this->layout = $layout;
  }
  public function render(AvailForm $form)
  {
    $html = <<<EOT
<h2>Arbiter Availability</h2>
{$form->render()}
<p>
Use <em>Choose File</em> to select the arbiter generated availability spreadsheet then press <em>Generate</em>.
</p>
EOT;
    $this->layout->setContent($html);
    return $this->layout->render();
  }
}