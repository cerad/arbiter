<?php
namespace Cerad\Security;

use Cerad\Component\Http\Router;

class SecurityRoutes
{
  public function __construct(Router $router)
  {
    $router->addRoute('security_login_route',['GET','POST'],'/login');
  }
}