<?php

use FastRoute\RouteCollector                as RouteCollector;
use FastRoute\RouteParser\Std               as RouteParser;
use FastRoute\Dispatcher\GroupCountBased    as RouteDispatcher;
use FastRoute\DataGenerator\GroupCountBased as RouteDataGenerator;

class Router
{
  protected $routes = [];
  protected $routeCollector;
  protected $routeDispatcher;

  public function __construct()
  {
    $this->routeCollector = new RouteCollector(new RouteParser(), new RouteDataGenerator());
  }
  public function addRoute($name, $methods, $pattern, array $attrs = [], $mws = [])
  {
    // Handy for testing
    $mws = is_array($mws) ? $mws : [['priority' => 0, 'callable' => $mws]];

    $this->routes[$name] = $route = [
      'name'     => $name,
      'attrs'    => $attrs,
      'methods'  => $methods,
      'pattern'  => $pattern,
      'mws'      => $mws,
      'vars'     => null,
    ];
    $this->routeCollector->addRoute($methods,$pattern,$name);
    return $route;
  }
  public function dispatch($method, $uri)
  {
    if ($this->routeDispatcher === null) {
      $this->routeDispatcher = new RouteDispatcher($this->routeCollector->getData());
    }
    $routeInfo = $this->routeDispatcher->dispatch($method,$uri);

    switch($routeInfo[0])
    {
      case RouteDispatcher::FOUND:
        $name  = $routeInfo[1];
        $route = $this->routes[$name];
        $route['vars'] = $routeInfo[2];
        return $route;
    }
    // Toss invalid route exception?
    throw new \UnexpectedValueException(sprintf('Route not found: %s %s',$method,$uri));
  }
}
