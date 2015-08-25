<?php
namespace Cerad\Component\Arbiter\Schedule\Show;

class ShowContent
{
  private $layout;

  public function __construct($layout)
  {
    $this->layout = $layout;
  }
  public function render()
  {
    $html = <<<EOT
<h2>Schedule Page</h2>
<div id="hello"></div>
<script type="text/javascript" src="assets/bundle.js"></script>
EOT;
    $this->layout->setContent($html);
    return $this->layout->render();
  }
}