<?php
namespace Cerad\Component\App;

class AppLayout
{
  // Blocks
  protected $content = '<h1>NO CONTENT</h1>';

  public function setContent($content)
  {
    $this->content = $content;
  }
  public function render()
  {
    return <<<EOT
<html>
<head>
  <title>Arbiter</title>
</head>
<body>
  <div id="content">
    {$this->content}
  </div>
</body>
</html>
EOT;
  }
}