<?php
namespace Cerad\Component\Arbiter\Schedule;

use Cerad\Component\Dic\Dic as Dic;

class ScheduleServices
{
  public function __construct(Dic $dic)
  {
    $dic['arbiter_schedule_show_content'] = function() use ($dic) {
      return new Show\ShowContent(
        $dic['app_layout']
      );
    };
    $dic['arbiter_schedule_show_action'] = function() use ($dic) {
      return new Show\ShowAction(
        $dic['arbiter_schedule_show_content']
      );
    };
  }
}