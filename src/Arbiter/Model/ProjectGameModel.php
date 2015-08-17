<?php
namespace Cerad\Component\Model;

class ProjectGameModel
{
  public $keys = [
    'id',

    'project', // Project relation
    'number',

    'project_field',  // ProjectField relation
    'project_level',  // This is the level for the game, different levels of teams might be assigned
    'project_league', // The league/organization owning the game?

    'start',   // Playing slot
    'finish',
    'length',  // Playing time

    'status',
  ];
}