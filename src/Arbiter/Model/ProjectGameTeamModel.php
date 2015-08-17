<?php
namespace Cerad\Component\Model;

class ProjectGameTeamModel
{
  public $keys = [
    'id',

    'project_game', // Game relation
    'project_team', // Team relation

    'project_level',  // Not really needed. Use game::project_level or team::project_level
    'project_league', // AKA Region, again not really needed?

    'source', // Win Game 27, Pool B 3rd place, maybe a relation?
    'source_game', 'source_game_result',
    'source_pool', 'source_pool_place',

    'score',

    'warnings', // Probably a game team report link
    'ejections',
    'sportsmanship',

    'status',
  ];
}