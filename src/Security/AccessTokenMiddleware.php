<?php
namespace Cerad\Security;

use Cerad\Component\Jwt\JwtCoder;

use Psr\Http\Message\ResponseInterface      as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AccessTokenMiddleware
{
  protected $storage;
  protected $jwtCoder;

  public function __construct(AccessTokenStorage $storage, JwtCoder $jwtCoder)
  {
    $this->storage  = $storage;
    $this->jwtCoder = $jwtCoder;
  }
  public function __invoke(Request $request, Response $response)
  {
    $accessToken = null;

    if ($request->hasHeader('Authorization')) {
      $accessTokenHeader = $request->getHeaderLine('Authorization');
      $accessToken = $this->jwtCoder->decode((substr($accessTokenHeader,7)));
    }
    else {
      $cookies = $request->getCookieParams();
      if (isset($cookies['access_token'])) {
        $accessToken = $this->jwtCoder->decode($cookies['access_token']);
      }
    }
    if ($accessToken) {
      $this->storage->set($accessToken);
      $request = $request->withAttribute('access_token',$accessToken);
    }
    return [$request,$response];
  }
}