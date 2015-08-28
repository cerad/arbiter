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

    /** ===================================================
     * Login/Logout
     */
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
    $dic['security_logout_action'] = function() use ($dic) {
      return new Logout\LogoutAction(
        $dic['security_access_token_storage']
      );
    };
    $dic['security_logout_route'] = function() use($dic)
    {
      return $dic['security_logout_action'];
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
    $dic['security_access_token_storage'] = function() {
      return new AccessTokenStorage();
    };
    $dic['access_token_middleware'] = function() use ($dic){
      return new AccessTokenMiddleware(
        $dic['security_access_token_storage'],
        $dic['jwt_coder']
      );
    };
  }
}