<?php
namespace Cerad\Component\Arbiter\App;

use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory as RequestFactory;
use Zend\Diactoros\Response\SapiEmitter as ResponseEmitter;

use Psr\Http\Message\ResponseInterface      as ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;

use Cerad\Component\Dic\Dic;
use Cerad\Component\Http\Router;

use Cerad\App\AppParameters;

use Cerad\Component\Arbiter\ArbiterRoutes;
use Cerad\Component\Arbiter\ArbiterServices;

use Cerad\Security\SecurityRoutes;
use Cerad\Security\SecurityServices;

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

    new SecurityRoutes($router);
    new ArbiterRoutes ($router);
  }
  protected function registerServices(Dic $dic)
  {
    new AppParameters($dic);

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
    new SecurityServices($dic);
    new ArbiterServices ($dic);

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

    /** @var callable $accessTokenMiddleware */
    $accessTokenMiddleware = $dic->get('access_token_middleware');

    $results = $accessTokenMiddleware($request,$response);

    $results = $this->route($results[0],$results[1]);

    $this->emit($results[0],$results[1]);
  }
  protected function route(RequestInterface $request, ResponseInterface $response)
  {
    $router = $this->dic->get('router');
    $path   = $request->getUri()->getPath();
    $route  = $router->dispatch($request->getMethod(),$path);

    /** @var callable $action */
    $action = $this->dic->get($route['name']);

    return $action($request,$response);
  }
  protected function emit(RequestInterface $request, ResponseInterface $response)
  {
    $emitter = $this->dic->get('response_emitter');
    $emitter->emit($response);
    return [$request,$response];
  }
}
