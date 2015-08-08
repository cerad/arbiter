<?php
namespace Cerad\Component\Arbiter\Index;

use Psr\Http\Message\ResponseInterface      as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class IndexAction
{
  private $content;

  public function __construct(IndexContent $content)
  {
    $this->content = $content;
  }
  public function __invoke(Request $request, Response $response)
  {
    $response->getBody()->write($this->content->render());

    return [$request,$response];
  }
}