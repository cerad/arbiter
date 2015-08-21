<?php
namespace Cerad\Component\Arbiter\Model;

class ProjectGameTeamModel implements \ArrayAccess
{
  protected $id;

  protected $project_game; // Game relation
  protected $project_team; // Team relation

  protected $slot;
  protected $source; // Win Game 27, Pool B 3rd place, maybe a relation?

  protected $score;
  protected $sportsmanship;

  protected $warnings; // Probably a game team report link
  protected $ejections;

  protected $status;

  public $keys = [
    'id' => true,

    'project_game' => true, // Game relation
    'project_team' => true, // Team relation

    //'project_level',  // Not really needed. Use game::project_level or team::project_level
    //'project_league', // AKA Region, again not really needed?

    'slot' => true,
    'source', // Win Game 27, Pool B 3rd place, maybe a relation?
    //source_game', 'source_game_result',
    //'source_pool', 'source_pool_place',

    'score' => true,
    'sportsmanship' => true,

    'warnings'  => true, // Probably a game team report link
    'ejections' => true,

    'status' => true,
  ];
  public function __construct(array $params = [])
  {
    foreach($params as $key => $value) {
      if (isset($this->keys[$key])) {
        $this->$key = $value;
      }
    }
  }
  public function offsetGet($key)
  {
    if (isset($this->keys[$key])) {
      return $this->$key;
    }
    throw new \UnexpectedValueException("ProjectGameTeamModel::offsetGet {$key}");
  }
  public function offsetSet($key,$value)
  {
    if (isset($this->keys[$key])) {
      return $this->$key = $value;
    }
    throw new \UnexpectedValueException("ProjectGameTeamModel::offsetSet {$key} {$value}");
  }
  public function offsetExists($key)
  {
    if (isset($this->keys[$key])) {
      return true;
    }
    return false;
  }
  public function offsetUnset($key)
  {
    throw new \BadMethodCallException("ProjectGameTeamModel::offsetUnset {$key}");
  }
}