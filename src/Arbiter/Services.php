<?php
namespace Cerad\Component\Arbiter;

use Pimple\Container as Dic;

use Cerad\Component\Arbiter\Avail\AvailCommand;
use Cerad\Component\Arbiter\Avail\AvailLoaderExcel;

class Services
{
  public function __construct(Dic $dic)
  {
    $dic['arbiter_avail_loader_excel'] = function() {
      return new AvailLoaderExcel();
    };
    $dic['arbiter_avail_command'] = function() use ($dic) {
      return new AvailCommand($dic['arbiter_avail_loader_excel']);
    };
  }
}