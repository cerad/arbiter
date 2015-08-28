<?php
namespace Cerad\Security;

use Cerad\Component\Dic\Dic;
use Cerad\Component\Jwt\JwtCoder;

use /** @noinspection PhpInternalEntityUsedInspection */
  Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

class SecurityServices
{
  public function __construct(Dic $dic)
  {
    /* ====================================
     * Database stuff
     */
    $dic['users_db_conn'] = function() use($dic)
    {
      /** @noinspection PhpInternalEntityUsedInspection */
      $config = new Configuration();
      $connParams =
        [
          'url' => $dic['users_db_url'],
          'driverOptions' => [\PDO::ATTR_EMULATE_PREPARES => false],
        ];
      return DriverManager::getConnection($connParams, $config);
    };

    // Login
    $dic['security_login_content'] = function() use ($dic) {
      return new Login\LoginContent(
        $dic['app_layout']
      );
    };
    $dic['security_login_form'] = function() use ($dic) {
      return new Login\LoginForm();
    };
    $dic['security_login_action'] = function() use ($dic) {
      return new Login\LoginAction(
        $dic['security_login_content'],
        $dic['security_login_form'],
        $dic['users_db_conn'],
        $dic['security_password_encoder'],
        $dic['jwt_coder']
      );
    };
    $dic['security_login_route'] = function() use($dic)
    {
      return $dic['security_login_action'];
    };
    /* ==================================================
     * Access token stuff
     */
    $dic['jwt_coder'] = function() {
      return new JwtCoder('secret');
    };
    $dic['security_password_encoder'] = function() {
      return new PasswordEncoder();
    };
    $dic['access_token_storage'] = function() {
      return new \Cerad\Security\AccessTokenStorage();
    };
    $dic['access_token_middleware'] = function() use ($dic){
      return new AccessTokenMiddleware(
        $dic['access_token_storage'],
        $dic['jwt_coder']
      );
    };
  }
}