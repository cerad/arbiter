<?php
namespace Cerad\Component\Arbiter\Model;

class ProjectModel implements \ArrayAccess
{
  protected $id;

  protected $project_key;
  protected $role;
  protected $name;
  protected $title;

  protected $domain;
  protected $domain_sub;
  protected $season;
  protected $sport;

  protected $start;
  protected $finish;
  protected $status;

  public $keys = [
    'id' => true,

    'project_key' => true,
    'role'        => true,
    'name'        => true,
    'title'       => true,

    'domain'     => true,
    'domain_sub' => true,
    'season'     => true,
    'sport'      => true,

    'start'  => true,
    'finish' => true,
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
    throw new \UnexpectedValueException("ProjectModel::offsetGet {$key}");
  }
  public function offsetSet($key,$value)
  {
    if (isset($this->keys[$key])) {
      return $this->$key = $value;
    }
    throw new \UnexpectedValueException("ProjectModel::offsetSet {$key} {$value}");
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
    throw new \BadMethodCallException("ProjectModel::offsetUnset {$key}");
  }
}