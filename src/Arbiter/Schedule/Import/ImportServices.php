<?php
namespace Cerad\Component\Arbiter\Schedule\Import;

use Cerad\Component\Dic\Dic as Dic;

class ImportServices
{
  public function __construct(Dic $dic)
  {
    $dic['arbiter_schedule_importer_games_with_slots_xml'] = function() use ($dic) {
      return new ImporterGamesWithSlotsXml(
        $dic['arbiter_db_conn']
      );
    };
    $dic['arbiter_schedule_import_command'] = function() use ($dic) {
      return new ImportCommand(
        $dic['arbiter_schedule_importer_games_with_slots_xml']
      );
    };
  }
}