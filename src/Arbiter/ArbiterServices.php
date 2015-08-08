<?php
namespace Cerad\Component\Arbiter;

use Cerad\Component\Dic\Dic as Dic;

use Cerad\Component\Arbiter\Index\IndexAction;
use Cerad\Component\Arbiter\Index\IndexContent;

use Cerad\Component\Arbiter\Avail\AvailServices;

class ArbiterServices
{
  public function __construct(Dic $dic)
  {
    // Index
    $dic['arbiter_index_content'] = function() use ($dic) {
      return new IndexContent(
        $dic['app_layout']
      );
    };
    $dic['arbiter_index_action'] = function() use ($dic) {
      return new IndexAction(
        $dic['arbiter_index_content']
      );
    };
    $dic['arbiter_index_route'] = function() use($dic)
    {
      return $dic['arbiter_index_action'];
    };
    // Availability
    $dic['arbiter_avail_route'] = function() use($dic)
    {
      // On demand loading
      new AvailServices($dic);
      return $dic['arbiter_avail_action'];
    };
  }
}