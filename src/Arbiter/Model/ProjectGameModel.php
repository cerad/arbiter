<?php
namespace Cerad\Component\Arbiter\Model;

class ProjectGameModel implements \ArrayAccess
{
  protected $id;

  protected $project; // Project relation
  protected $number;

  protected $project_field;  // ProjectField relation
  protected $project_level;  // This is the level for the game, different levels of teams might be assigned
  protected $project_league; // The league/organization owning the game?

  protected $project_game_teams     = [];
  protected $project_game_officials = [];

  protected $start;   // Playing slot
  protected $finish;
  protected $length;  // Playing time

  protected $status;

  public $keys = [
    'id' => true,

    'project' => true, // Project relation
    'number'  => true,

    'project_field'  => true,  // ProjectField relation
    'project_level'  => true,  // This is the level for the game, different levels of teams might be assigned
    'project_league' => true,  // The league/organization owning the game?

    'project_game_teams'     => true,
    'project_game_officials' => true,

    'start'  => true,   // Playing slot
    'finish' => true,
    'length' => true,  // Playing time

    'status' => true,
  ];
  public function __construct(array $params = [])
  {
    foreach($params as $key => $value) {
      if (isset($this->keys[$key])) {
        $this->$key = $value;
      }
    }
    if (!isset($params['no_teams'])) {
      $this->project_game_teams = [
        'home' => new ProjectGameTeamModel(['slot' => 'home', 'project_game' => $this]),
        'away' => new ProjectGameTeamModel(['slot' => 'away', 'project_game' => $this]),
      ];
    }
  }
  public function offsetGet($key)
  {
    if (isset($this->keys[$key])) {
      return $this->$key;
    }
    throw new \UnexpectedValueException("ProjectGameModel::offsetGet {$key}");
  }
  public function offsetSet($key,$value)
  {
    if (isset($this->keys[$key])) {
      return $this->$key = $value;
    }
    throw new \UnexpectedValueException("ProjectGameModel::offsetSet {$key} {$value}");
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
    throw new \BadMethodCallException("ProjectGameModel::offsetUnset {$key}");
  }
}