<?php
namespace Cerad\Component\Arbiter\App;

use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory as RequestFactory;
use Zend\Diactoros\Response\SapiEmitter as ResponseEmitter;

use Cerad\Component\Dic\Dic;
use Cerad\Component\Http\Router;

use Cerad\Component\Arbiter\ArbiterRoutes;
use Cerad\Component\Arbiter\ArbiterServices;

class AppKernel
{
  protected $dic;

  public function __construct()
  {
    $this->dic = $dic = $this->createDependencyInjectionContainer();

    $this->registerServices($dic);
    $this->registerRoutes  ($dic->get('router'));
  }
  protected function createDependencyInjectionContainer()
  {
    return new Dic();
  }
  protected function registerRoutes(Router $router)
  {
    $router->addRoute('arbiter_index_route','GET','/');
    new ArbiterRoutes($router);
  }
  protected function registerServices(Dic $dic)
  {
    $dic['router'] = function() {
      return new Router;
    };
    $dic['request'] = $dic->factory(function() {
      return RequestFactory::fromGlobals();
    });
    $dic['response'] = $dic->factory(function() {
      return new Response();
    });
    $dic['response_emitter'] = function() {
      return new ResponseEmitter();
    };
    $dic['app_layout'] = function() use ($dic) {
      return new AppLayout(
        $dic['app_main_menu']
      );
    };
    $dic['app_main_menu'] = function() use ($dic) {
      return new AppMainMenu(
        $dic['router'] // Maybe
      );
    };
    new ArbiterServices($dic);

    /* =========================================================
     * Example of defining a callable service, not used but keep for now
    $dic['app_index_route'] = $dic->protect(function(RequestInterface $request, ResponseInterface $response)
    {
      ob_start();
      require 'views/index.html.php';
      $response->getBody()->write(ob_get_clean());
      return [$request,$response];
    });*/
  }
  public function run()
  {
    $dic = $this->dic;

    $request  = $dic->get('request');
    $response = $dic->get('response');
    $router   = $dic->get('router');

    $path = $request->getUri()->getPath();
    $route = $router->dispatch($request->getMethod(),$path);

    /** @var callable $action */
    $action = $dic->get($route['name']);

    $results  = $action($request,$response);
    $response = $results[1];

    $emitter = $dic->get('response_emitter');
    $emitter->emit($response);
  }
}
