<?php

use Cerad\Component\Security\UserProvider;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Query;

class UserProviderTest extends \PHPUnit_Framework_TestCase
{
  protected static $dbConn;

  public static function setUpBeforeClass()
  {
    $connConfig = new Configuration();
    $connParams = [
      'url' => 'mysql://test:@localhost/tourns',
      'driverOptions' => [\PDO::ATTR_EMULATE_PREPARES => false],
    ];
    self::$dbConn = DriverManager::getConnection($connParams, $connConfig);
  }
  public function testLoad()
  {
    $userProvider = new UserProvider(self::$dbConn);

    $user = $userProvider->loadUserByUsername('ahundiak@nasoa.org');

    $this->assertEquals(1,$user['id']);
  }
  /**
   * @expectedException Symfony\Component\Security\Core\Exception\UsernameNotFoundException
   */
  public function testUserNotFound()
  {
    $userProvider = new UserProvider(self::$dbConn);

    /** @noinspection PhpUnusedLocalVariableInspection */
    $user = $userProvider->loadUserByUsername('ahundiak@nasoa.fake');
  }
}