<?php

use Cerad\Component\Jwt\JwtCoder;

use Cerad\Security\AccessTokenStorage;
use Cerad\Security\AccessTokenMiddleware;

use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest as Request;
use Zend\Diactoros\ServerRequestFactory as RequestFactory;

class AccessTokenMiddlewareTest extends PHPUnit_Framework_TestCase
{
  protected function createAccessToken($jwtCoder)
  {
    return $accessToken = $jwtCoder->encode([
      'iss'    =>  'cerad',
      'scopes' =>  ['user','admin'],
      'id'     => 36,
    ]);
  }
  public function testHeader()
  {
    $jwtCoder = new JwtCoder('secret');
    $storage = new AccessTokenStorage();

    $server = [
      'HTTP_Authorization' => 'Bearer ' . $this->createAccessToken($jwtCoder),
    ];
    $request = RequestFactory::fromGlobals($server);
    $this->assertTrue($request->hasHeader('Authorization'));

    $response = new Response();

    $mw = new AccessTokenMiddleware($storage,$jwtCoder);

    $results = $mw($request,$response);

    /** @var Request $request */
    $request = $results[0];

    $accessToken = $request->getAttribute('access_token');
    $this->assertTrue(is_array($accessToken));
    $this->assertEquals(36,$accessToken['id']);

    $accessToken = $storage->get();
    $this->assertEquals(36,$accessToken['id']);
  }
  public function testCookie()
  {
    $jwtCoder = new JwtCoder('secret');
    $storage = new AccessTokenStorage;

    $cookies = [
      'access_token' => $this->createAccessToken($jwtCoder),
    ];
    $request = RequestFactory::fromGlobals(null,null,null,$cookies);

    $response = new Response();

    $mw = new AccessTokenMiddleware($storage,$jwtCoder);

    $results = $mw($request,$response);

    /** @var Request $request */
    $request = $results[0];

    $accessToken = $request->getAttribute('access_token');
    $this->assertTrue(is_array($accessToken));
    $this->assertEquals(36,$accessToken['id']);

    $accessToken = $storage->get();
    $this->assertEquals(36,$accessToken['id']);
  }
}