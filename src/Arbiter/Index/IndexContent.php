<?php
namespace Cerad\Component\Arbiter\Index;

class IndexContent
{
  private $layout;

  public function __construct($layout)
  {
    $this->layout = $layout;
  }
  public function render()
  {
    $html = <<<EOT
<h2>Arbiter Index Page</h2>
EOT;
    $this->layout->setContent($html);
    return $this->layout->render();
  }
}