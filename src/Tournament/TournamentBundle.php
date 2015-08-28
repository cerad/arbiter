<?php
namespace Cerad\Tournament;

use Cerad\Component\Dic\Dic;
use Cerad\Component\Http\Router;

class TournamentBundle
{
  public function registerServices(Dic $dic)
  {
    /** ===================================================
     * Project Official Register
     */
    $dic['tournament_project_official_register_content'] = function() use ($dic) {
      return new ProjectOfficial\Register\RegisterContent(
        $dic['app_layout'],
        $dic['tournament_project_official_register_form']
      );
    };
    $dic['tournament_project_official_register_form'] = function() use ($dic) {
      return new ProjectOfficial\Register\RegisterForm();
    };
    $dic['tournament_project_official_register_action'] = function() use ($dic) {
      return new ProjectOfficial\Register\RegisterAction(
        $dic['tournament_project_official_register_content'],
        $dic['tournament_project_official_register_form']
      );
    };
    $dic['tournament_project_official_register_route'] = function() use($dic)
    {
      return $dic['tournament_project_official_register_action'];
    };
  }
  public function registerRoutes(Router $router)
  {
    $router->addRoute('tournament_project_official_register_route',['GET','POST'],'/register');
  }
}