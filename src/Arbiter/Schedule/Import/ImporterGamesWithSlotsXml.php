<?php
namespace Cerad\Component\Arbiter\Schedule\Import;

use Doctrine\DBAL\Connection;

use Symfony\Component\Stopwatch\Stopwatch;

class ImporterGamesWithSlotsXml
{
  protected $dbConn;

  protected $results;

  protected $sport;
  protected $domain;
  protected $season;

  protected $teams     = [];
  protected $levels    = [];
  protected $fields    = [];
  protected $projects  = [];
  protected $officials = [];

  public function __construct(Connection $dbConn)
  {
    $this->dbConn = $dbConn;
  }
  /* =======================================================================
   * Process a project
   */
  public function processProject($domainSub)
  {
    // Cache
    $key = "{$this->domain} {$this->sport} {$this->season} {$domainSub}";
    if (isset($this->projects[$key])) return $this->projects[$key];

    // Existing
    $sql = 'SELECT id FROM projects WHERE domain = ? AND sport = ? AND season = ? AND domain_sub = ?;';
    $stmt = $this->dbConn->executeQuery($sql,[$this->domain,$this->sport,$this->season,$domainSub]);
    $rows = $stmt->fetchAll();
    if (count($rows)) {
      return $this->projects[$key] = $rows[0]['id'];
    }
    // New
    $params = [
      'domain'     => $this->domain,
      'domain_sub' => $domainSub,
      'sport'      => $this->sport,
      'season'     => $this->season,
      'status'     => 'Active',
    ];
    $this->dbConn->insert('projects',$params);
    $projectId = $this->dbConn->lastInsertId();
    return $this->projects[$key] = $projectId;
  }
  /* =======================================================================
   * Process a project level
   */
  public function processLevel($projectId,$levelName)
  {
    // Cache
    $key = "{$projectId} {$levelName}";
    if (isset($this->levels[$key])) return $this->levels[$key];

    // Existing
    $sql = 'SELECT id FROM project_levels WHERE project_id = ? AND name = ?;';
    $stmt = $this->dbConn->executeQuery($sql,[$projectId,$levelName]);
    $rows = $stmt->fetchAll();
    if (count($rows)) {
      return $this->levels[$key] = $rows[0]['id'];
    }
    // New
    $params = [
      'project_id' => $projectId,
      'name'       => $levelName,
      'status'     => 'Active',
    ];
    $this->dbConn->insert('project_levels',$params);
    $levelId = $this->dbConn->lastInsertId();
    return $this->levels[$key] = $levelId;
  }
  /* =======================================================================
   * Process a project field
   */
  public function processField($projectId,$siteName,$fieldName)
  {
    if (!$fieldName) {
      $fieldName = $siteName;
    }
    else {
      $fieldName = "{$siteName} {$fieldName}";
    }
    // Cache
    $key = "{$projectId} {$fieldName}";
    if (isset($this->fields[$key])) return $this->fields[$key];

    // Existing
    $sql = 'SELECT id FROM project_fields WHERE project_id = ? AND name = ?;';
    $stmt = $this->dbConn->executeQuery($sql,[$projectId,$fieldName]);
    $rows = $stmt->fetchAll();
    if (count($rows)) {
      return $this->fields[$key] = $rows[0]['id'];
    }
    // New
    $params = [
      'project_id' => $projectId,
      'name'       => $fieldName,
      'status'     => 'Active',
    ];
    $this->dbConn->insert('project_fields',$params);
    $fieldId = $this->dbConn->lastInsertId();
    return $this->fields[$key] = $fieldId;
  }
  /* =======================================================================
   * Process a game
   * Here is where we start looking for updates
   */
  public function processGame($projectId,$fieldId,$levelId,$number,$start,$finish,$status,$note,$noteDate)
  {
    $start  = str_replace('T',' ',$start );
    $finish = str_replace('T',' ',$finish);

    if ($note     === '') $note     = null;
    if ($noteDate === '') $noteDate = null;

    // Existing
    $sql = 'SELECT * FROM project_games WHERE project_id = ? AND number = ?;';
    $stmt = $this->dbConn->executeQuery($sql,[$projectId,$number]);
    $rows = $stmt->fetchAll();
    if (count($rows)) {
      return $rows[0]; // Need to check for updates
    }
    // New
    $params = [
      'project_id'       => $projectId,
      'number'           => $number,
      'project_field_id' => $fieldId,
      'project_level_id' => $levelId,
      'status'           => $status,
      'start'            => $start,
      'finish'           => $finish,
      'note'             => $note,
      'note_date'        => $noteDate,
    ];
    $this->dbConn->insert('project_games',$params);
    $params['id'] = $this->dbConn->lastInsertId();
    return $params;
  }
  /* =======================================================================
   * Process a project team
   */
  protected function processProjectTeam($projectId,$levelId,$name)
  {
    if (!$name) { return null; }

    // Cache
    $key = "{$projectId} {$levelId} {$name}";
    if (isset($this->teams[$key])) return $this->teams[$key];

    // Existing
    $sql = 'SELECT id FROM project_teams WHERE project_id = ? AND project_level_id = ? AND name = ?;';
    $stmt = $this->dbConn->executeQuery($sql,[$projectId,$levelId,$name]);
    $rows = $stmt->fetchAll();
    if (count($rows)) {
      return $this->teams[$key] = $rows[0]['id'];
    }
    // New
    $params = [
      'project_id'       => $projectId,
      'project_level_id' => $levelId,
      'name'             => $name,
    ];
    $this->dbConn->insert('project_teams',$params);
    return $this->teams[$key] = $this->dbConn->lastInsertId();
  }
  /* =======================================================================
   * Process a project game team
   *
   */
  protected function processProjectGameTeam($gameId,$slot,$teamId,$score)
  {
    // TODO: Verify a score of 0 comes across correctly
    if ($score === '') $score = null;
    $score = $score === null ? null : (integer)$score;

    $sql = 'SELECT * FROM project_game_teams WHERE project_game_id = ? AND slot = ?;';
    $stmt = $this->dbConn->executeQuery($sql,[$gameId,$slot]);
    $rows = $stmt->fetchAll();
    if (count($rows)) {
      return $rows[0]; // Need to check for updates
    }
    // New
    $params = [
      'project_game_id'  => $gameId,
      'project_team_id'  => $teamId,
      'slot'             => $slot,
      'score'            => $score,
    ];
    $this->dbConn->insert('project_game_teams',$params);
    return $this->dbConn->lastInsertId();
  }
  /* =======================================================================
   * Process a project official
   */
  protected function processProjectOfficial($projectId,$name,$email)
  {
    if (!$name) { return null; }

    // Cache
    $key = "{$projectId} {$name}";
    if (isset($this->officials[$key])) return $this->officials[$key];

    // Existing
    $sql = 'SELECT id FROM project_officials WHERE project_id = ? AND name = ?;';
    $stmt = $this->dbConn->executeQuery($sql,[$projectId,$name]);
    $rows = $stmt->fetchAll();
    if (count($rows)) {
      return $this->officials[$key] = $rows[0]['id'];
    }
    // New
    $params = [
      'project_id' => $projectId,
      'name'       => $name,
      'email'      => $email,
    ];
    $this->dbConn->insert('project_officials',$params);
    return $this->officials[$key] = $this->dbConn->lastInsertId();
  }
  /* =======================================================================
   * Process a project game official
   * This can be tricky as the number of officials can change from three man to dual
   *  But worry more about that for high school
   */
  protected function processProjectGameOfficial($gameId,$slot,$officialId)
  {
    // No slot assigned to the game
    if (substr($slot,0,2) === 'No') return null;

    // TODO: Normalize slots

    // Existing
    $sql  = 'SELECT * FROM project_game_officials WHERE project_game_id = ? AND slot = ?;';
    $stmt = $this->dbConn->executeQuery($sql,[$gameId,$slot]);
    $rows = $stmt->fetchAll();
    if (count($rows)) {
      return $rows[0]['id']; // Need to check for updates
    }
    // New
    $params = [
      'project_game_id'     => $gameId,
      'project_official_id' => $officialId,
      'slot'                => $slot,
    ];
    $this->dbConn->insert('project_game_officials',$params);
    return $this->dbConn->lastInsertId();
  }
  /* =======================================================================
   * Process a single row of data
   *
   */
  protected function processRow($row)
  {
    //print_r($row); die();
    $projectId = $this->processProject($row['domain_sub']);

    $levelId = $this->processlevel($projectId,$row['level']);
    $fieldId = $this->processField($projectId,$row['site'],$row['siteSub']);

    $game = $this->processGame($projectId,$fieldId,$levelId,
      $row['number'],$row['start'],$row['finish'],$row['status'],
      $row['gameNote'],$row['gameNoteDate']
    );
    $homeTeamId = $this->processProjectTeam($projectId,$levelId,$row['homeTeamName']);
    $awayTeamId = $this->processProjectTeam($projectId,$levelId,$row['awayTeamName']);

    $this->processProjectGameTeam($game['id'],'home',$homeTeamId,$row['homeTeamScore']);
    $this->processProjectGameTeam($game['id'],'away',$awayTeamId,$row['awayTeamScore']);

    for($slotIndex = 1; $slotIndex < 6; $slotIndex++) {
      $officialId = $this->processProjectOfficial($projectId,$row['officialName' . $slotIndex], $row['officialEmail' . $slotIndex]);

      $this->processProjectGameOfficial($game['id'],$row["officialSlot{$slotIndex}"],$officialId);
    }
  }
  /* =======================================================================
   * Main Entry point
   *
   */
  public function import($params)
  {
    $stopwatch = new Stopwatch();
    $stopwatch->start('import');

    $this->results = $results = new ImportResults;
    $results->filename = $filename = $params['filename'];
    $results->basename = $params['basename'];

    $this->sport  = $params['sport'];
    $this->domain = $params['domain'];
    $this->season = $params['season'];

    // Must be a report file
    $reader = new \XMLReader();
    $reader->open($filename,null,LIBXML_COMPACT | LIBXML_NOWARNING);

    // Position to Report node
    if (!$reader->next('Report'))
    {
      $results->message = '*** Not a Report file';
      $reader->close();
      return $results;
    }
    // Verify report type
    $reportType = $reader->getAttribute('Name');
    switch($reportType)
    {
      case 'Games with Slots': // Pre Fall 2014
      case 'Games_with_Slots_1':
        break;
      default:
        $results->message = '*** Unexpected report type: ' . $reportType;
        $reader->close();
        return $results;
    }
    // Kind of screw but oh well
    while ($reader->read() && $reader->name !== 'Detail');

    while($reader->name == 'Detail')
    {
      $row = array();

      foreach($this->map as $key => $attr)
      {
        $row[$key] = trim($reader->getAttribute($attr));
      }
      $results->countGamesTotal++;

      $this->processRow($row);

      // On to the next one
      $reader->next('Detail');
    }
    $reader->close();

    $event = $stopwatch->stop('import');
    $results->memory   = $event->getMemory();
    $results->duration = $event->getDuration();


    return $results;
  }
  /* ===========================================================
   * xmlReader does not have a simple getAttributes command
   */
  protected $map = array
  (
    'number'        => 'GameID',
    'start'         => 'From_Date',    // 2013-03-08T16:30:00
    'finish'        => 'To_Date',
    'domain_sub'    => 'Sport',        // AHSAA
    'level'         => 'Level',        // MS-B
    'site'          => 'Site',
    'siteSub'       => 'Subsite',
    'homeTeamName'  => 'Home_Team',
    'homeTeamScore' => 'Home_Score',
    'awayTeamName'  => 'Away_Team',
    'awayTeamScore' => 'Away_Score',

    'status'        => 'Status',

    'officialSlots' => 'Slots_Total',

    'officialSlot1' => 'First_Position',  // Referee
    'officialSlot2' => 'Second_Position', // AR1 (or possibly dual?
    'officialSlot3' => 'Third_Position',  // AR2
    'officialSlot4' => 'Fourth_Position', // 'No Fourth Position'
    'officialSlot5' => 'Fifth_Position',  // 'No Fifth Position'

    'officialName1' => 'First_Official',
    'officialName2' => 'Second_Official',
    'officialName3' => 'Third_Official',
    'officialName4' => 'Fourth_Official',  // 'Empty'
    'officialName5' => 'Fifth_Official',   // 'Empty'

    'officialEmail1' => 'First_Email',
    'officialEmail2' => 'Second_Email',
    'officialEmail3' => 'Third_Email',
    'officialEmail4' => 'Fourth_Email',  // 'Empty'
    'officialEmail5' => 'Fifth_Email',   // 'Empty'

    'billTo'        => 'BillTo_Name',
    'billAmount'    => 'Bill_Amount',     // 100.00
    'billFees'      => 'Total_Game_Fees', //  37.00 ?

    'gameNote'      => 'Game_Note',    // 'No Note'
    'gameNoteDate'  => 'Note_Date=',   //  Blank

    'gameReportComments' => 'Game_Report_Comments',
  //'gameReportDateTime' => 'Report_Posted_Date',   // 1900-01-01T00:00:00
    'gameReportDate'     => 'Report_Posted_Date',   // 08/10/15
    'gameReportStatus'   => 'Report_Status',        // 'No Report'
    'gameReportOfficial' => 'Reporting_Official',
  );
}