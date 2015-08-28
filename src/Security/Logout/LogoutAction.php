<?php
namespace Cerad\Security\Logout;

use Psr\Http\Message\ResponseInterface      as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Symfony\Component\HttpFoundation\Cookie;

class LogoutAction
{
  public function __invoke(Request $request, Response $response)
  {
    $cookie = new Cookie('access_token', null);

    /** @var Response $response */
    $response = $response->withAddedHeader('Set-Cookie', $cookie->__toString());
    /** @var Response $response */
    $response = $response->withStatus(302);
    /** @var Response $response */
    $response = $response->withHeader('Location', '/');

    return [$request, $response];
  }
}