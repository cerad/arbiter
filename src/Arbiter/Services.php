<?php
namespace Cerad\Component\Arbiter;

use Pimple\Container as Dic;

use Cerad\Component\Arbiter\Avail\AvailCommand;
use Cerad\Component\Arbiter\Avail\AvailController;
use Cerad\Component\Arbiter\Avail\AvailLoaderExcel;
use Cerad\Component\Arbiter\Avail\AvailReporterExcel;

class Services
{
  public function __construct(Dic $dic)
  {
    $dic['arbiter_avail_loader_excel'] = function() {
      return new AvailLoaderExcel();
    };
    $dic['arbiter_avail_reporter_excel'] = function() {
      return new AvailReporterExcel();
    };
    $dic['arbiter_avail_controller'] = function() use ($dic) {
      return new AvailController(
        $dic['arbiter_avail_loader_excel'],
        $dic['arbiter_avail_reporter_excel']
      );
    };
    $dic['arbiter_avail_route'] = function() use($dic)
    {
      return $dic['arbiter_avail_controller'];
    };
    $dic['arbiter_avail_command'] = function() use ($dic) {
      return new AvailCommand(
        $dic['arbiter_avail_loader_excel'],
        $dic['arbiter_avail_reporter_excel']
      );
    };
  }
}