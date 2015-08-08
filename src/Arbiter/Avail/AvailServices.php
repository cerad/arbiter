<?php
namespace Cerad\Component\Arbiter\Avail;

use Cerad\Component\Dic\Dic as Dic;

class AvailServices
{
  public function __construct(Dic $dic)
  {
    // Availability
    $dic['arbiter_avail_loader_excel'] = $dic->factory(function() {
      return new AvailLoaderExcel();
    });
    $dic['arbiter_avail_reporter_excel'] = $dic->factory(function() {
      return new AvailReporterExcel();
    });
    $dic['arbiter_avail_content'] = function() use ($dic) {
      return new AvailContent(
        $dic['app_layout']
      );
    };
    $dic['arbiter_avail_form'] = $dic->factory(function() {
      return new AvailForm();
    });
    $dic['arbiter_avail_action'] = function() use ($dic) {
      return new AvailAction(
        $dic['arbiter_avail_content'],
        $dic['arbiter_avail_form'],
        $dic['arbiter_avail_loader_excel'],
        $dic['arbiter_avail_reporter_excel']
      );
    };
    $dic['arbiter_avail_command'] = function() use ($dic) {
      return new AvailCommand(
        $dic['arbiter_avail_loader_excel'],
        $dic['arbiter_avail_reporter_excel']
      );
    };
  }
}