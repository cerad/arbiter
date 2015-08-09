<?php
namespace Cerad\Component\Arbiter\App;

class AppMainMenu
{
  public function render()
  {
    return <<<EOT
<style>
  ul.menu-horz { list-style-type: none; margin: 0; padding: 0; overflow: hidden; }
  ul.menu-horz li   { float: left; }
  ul.menu-horz li a { display: block; padding-right: 5px; color: green; }
</style>
<ul class="menu-horz">
  <li><a href="/"        >Home</a></li>
  <li><a href="/schedule">Schedule</a></li>
  <li><a href="/avail"   >Availability</a></li>
</ul>
EOT;
  }
}