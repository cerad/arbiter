<?php
namespace Cerad\Component\Model;

class ProjectModel
{
  public $keys = [
    'id',
    'key',
    'role',
    'name',
    'title',

    'domain',
    'league', // Domain might be more descriptive here
    'season',
    'sport',

    'start',
    'finish',
    'status',
  ];
}