<?php
namespace Cerad\Component\Arbiter\Avail;

class AvailLoaderExcel
{
  protected $date     = null;
  protected $official = null;

  protected $officials = [];

  protected function processRow($results,$row)
  {
    if (count($row) < 7) {
      print_r($row); die('Short row');
    }
    if ($row[4] === 'Officials Availability Report') {
      return;
    }
    if ($row[6] === 'Created by ArbiterSports.com')  {
      return;
    }

    if (($row[0] === 'Official' && $row[1] == 'Rank')) {
      return;
    }
    $colName = trim($row[0]);
    $colRank = trim($row[1]);
    $colAvail = trim($row[7]);

    if (substr($colAvail,0,12) == 'Open All Day') {
      $colAvail = 'Open All Day';
    }
    if ($colAvail === 'Blocked   12:00A 11:59P') {
      $colAvail = 'Blocked ALL DAY';
    }
    // Date change
    if (strpos($colName,'Referee Availability for') !== false) {
      $this->addOfficial();
      $pos = strrchr($colName,' ');
      $date = substr($colName,$pos * -1);
      $parts = explode('/',$date);
      $date = sprintf('%04d-%02d-%02d',$parts[2],$parts[0],$parts[1]);
      $this->date = $results->dates[] = $date;
      return;
    }

    // Both name and rank
    if ($colName && $colRank) {
      $this->addOfficial();

      $this->official = $official = [
        'name' => $colName,
        'rank' => $colRank,
        'city' => $row[ 4],
        'home' => $row[10],
        'cell' => $row[12],
        'avail'=> [],
      ];
      $this->official['avail'][$this->date][] = $colAvail;
      return;
    }
    if ($colName) {

      $name = $this->official['name'];

      // Drop trailing comma
      if (substr($name,-1) === ',') $name = substr($name,0,strlen($name)-1);

      $name = strchr($name,',') === false ? $name . ', ' . $colName : $name . ' ' . $colName;

      $this->official['name'] = $name;
    }
    if ($colAvail) {
      $this->official['avail'][$this->date][] = $colAvail;
    }
    return;

    //print_r($row); die();
  }
  protected function addOfficial()
  {
    if (!$this->official) return;

    $official = $this->official;
    $name = $official['name'];

    if (!isset($this->officials[$name])) {
      $this->officials[$name] = $official;
    }
    else {
      $date = $this->date;
      $this->officials[$name]['avail'][$date] = $official['avail'][$date];
    }
    $this->official = null;
  }
  public function load($filename)
  {
    $results = new AvailLoaderResults();
    $results->filename = $filename;

    $reader = new \PHPExcel_Reader_Excel5();
    $reader->setReadDataOnly(true);
    if (!$reader->canRead($filename)) {
      $results->errors[] = 'Could not open file for reading';
      return $results;
    }
    $excel = $reader->load($filename);
    $ws    = $excel->getSheet(0);
    $rows  = $ws->toArray();
    foreach($rows as $row) {
      $this->processRow($results,$row);
    }
    $this->addOfficial();

    $results->officials = $this->officials;

    return $results;
  }
}