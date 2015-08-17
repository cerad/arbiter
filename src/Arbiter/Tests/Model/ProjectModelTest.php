<?php

use Cerad\Component\Arbiter\Model\ProjectModel as Project;

class ProjectModelTest extends \PHPUnit_Framework_TestCase
{
  public function test1()
  {
    $project = new Project(['name' => 'test', 'status' => 'Active']);

    $this->assertEquals($project['name'],'test');

    $project['name'] = 'Test2';
    $this->assertEquals($project['name'],'Test2');

    $this->assertFalse(isset($project['name2']));
  }
}