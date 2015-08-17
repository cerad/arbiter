<?php

use Cerad\Component\Arbiter\Model\ProjectModel     as Project;
use Cerad\Component\Arbiter\Model\ProjectGameModel as ProjectGame;

class ProjectGameModelTest extends \PHPUnit_Framework_TestCase
{
  public function test1()
  {
    $game = new ProjectGame();

    $this->assertFalse(isset($game['project']['name']));

    $project = new Project(['name' => 'test', 'status' => 'Active']);

    $game['project'] = $project;

    $this->assertTrue(isset($game['project']['name']));

    $this->assertEquals($game['project']['name'],'test');

    $this->assertFalse(isset($game['project_game_teams']['home']['project_team']['name']));

  }
}