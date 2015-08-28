<?php
namespace Cerad\Tournament\ProjectOfficial\Register;

use Psr\Http\Message\ResponseInterface      as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class RegisterAction
{
  private $form;
  private $content;

  public function __construct(
    RegisterContent $content,
    RegisterForm    $form
  )
  {
    $this->form    = $form;
    $this->content = $content;
  }
  public function __invoke(Request $request, Response $response)
  {
    $form = $this->form;

    $form->handleRequest($request);

    if ($form->isValid()) {

    }
    $response->getBody()->write($this->content->render());
    return [$request,$response];
  }
}