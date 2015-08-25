<?php
namespace Cerad\Component\Arbiter;

use Cerad\Component\Dic\Dic as Dic;

use /** @noinspection PhpInternalEntityUsedInspection */
  Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

class ArbiterServices
{
  public function __construct(Dic $dic)
  {
    // Index
    $dic['arbiter_index_content'] = function() use ($dic) {
      return new Index\IndexContent(
        $dic['app_layout']
      );
    };
    $dic['arbiter_index_action'] = function() use ($dic) {
      return new Index\IndexAction(
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
      new Avail\AvailServices($dic);
      return $dic['arbiter_avail_action'];
    };
    // Schedule
    $dic['arbiter_schedule_show_route'] = function() use($dic)
    {
      // On demand loading
      new Schedule\ScheduleServices($dic);
      return $dic['arbiter_schedule_show_action'];
    };
    // Database connection
    $dic['arbiter_db_conn'] = function() use($dic)
    {
      /** @noinspection PhpInternalEntityUsedInspection */
      $config = new Configuration();
      $connParams =
        [
          'url' => $dic['arbiter_db_url'],
          'driverOptions' => [\PDO::ATTR_EMULATE_PREPARES => false],
        ];
      return DriverManager::getConnection($connParams, $config);
    };
  }
}