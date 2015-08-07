<?php

error_reporting(E_ALL);
date_default_timezone_set('America/Chicago');

require '../vendor/autoload.php';
require 'Router.php';

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;

use Zend\Diactoros\Response      as Response;
use Zend\Diactoros\ServerRequest as Request;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response\SapiEmitter as ResponseEmitter;

use Pimple\Container as Dic;

$dic = new Dic();
new \Cerad\Component\Arbiter\Services($dic);

$dic['index_route'] = $dic->protect(function(RequestInterface $request)
{
  $response = new Response();

  ob_start();
  require 'views/index.html.php';
  $response->getBody()->write(ob_get_clean());
  return $response;
});
$router = new Router();
$router->addRoute('index_route','GET','/');
$router->addRoute('arbiter_avail_route',['GET','POST'],'/avail');

$request = ServerRequestFactory::fromGlobals();

// Got to be a better way
$path = $request->getUri()->getPath();
if (substr($path,0,8) === '/app.php') $path = substr($path,8);
if (!$path) $path = '/';

$route = $router->dispatch($request->getMethod(),$path);

/** @var callable $action */
$action = $dic[$route['name']];

$response = $action($request);

// Emit Response
$emitter = new ResponseEmitter();
$emitter->emit($response);


