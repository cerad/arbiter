<?php
namespace Cerad\Component\Arbiter;

use Cerad\Component\Http\Router;

class ArbiterRoutes
{
  public function __construct(Router $router)
  {
    $router->addRoute('arbiter_avail_route',['GET','POST'],'/avail');

    $router->addRoute('arbiter_schedule_show_route',['GET','POST'],'/schedule');
  }
}