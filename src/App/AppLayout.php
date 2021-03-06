<?php
namespace Cerad\App;

class AppLayout
{
  // Blocks
  protected $content = '<h1>NO CONTENT</h1>';
  protected $mainMenu;

  public function __construct($mainMenu)
  {
    $this->mainMenu = $mainMenu;
  }
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
  <div id="main-menu">{$this->mainMenu->render() }</div>
  <div id="content">{$this->content}</div>
</body>
</html>
EOT;
  }
}