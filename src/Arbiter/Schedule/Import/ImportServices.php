<?php
namespace Cerad\Component\Arbiter\Schedule\Import;

use Cerad\Component\Dic\Dic as Dic;

class ImportServices
{
  public function __construct(Dic $dic)
  {
    $dic['arbiter_schedule_import_command'] = function() use ($dic) {
      return new ImportCommand(
        //$dic['arbiter_avail_loader_excel'],
        //$dic['arbiter_avail_reporter_excel']
      );
    };
  }
}