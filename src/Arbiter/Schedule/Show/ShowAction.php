<?php
namespace Cerad\Component\Arbiter\Schedule\Show;

use Psr\Http\Message\ResponseInterface      as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ShowAction
{
  private $content;

  public function __construct(ShowContent $content)
  {
    $this->content = $content;
  }
  public function __invoke(Request $request, Response $response)
  {
    $response->getBody()->write($this->content->render());

    return [$request,$response];
  }
}