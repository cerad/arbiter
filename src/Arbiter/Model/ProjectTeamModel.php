<?php
namespace Cerad\Component\Model;

class ProjectTeamModel
{
  public $keys = [
    'id',
    'team_key',
    'project',       // Project Relation, might be redundant
    'project_level', // Project Level Relation

    'name',
    'title',

    'status',
  ];
}