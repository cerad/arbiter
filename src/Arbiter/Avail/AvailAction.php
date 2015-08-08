<?php
namespace Cerad\Component\Arbiter\Avail;

use Psr\Http\Message\ResponseInterface      as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AvailAction
{
  private $form;
  private $content;
  private $loader;
  private $reporter;

  public function __construct(
    AvailContent       $content,
    AvailForm          $form,
    AvailLoaderExcel   $loader,
    AvailReporterExcel $reporter
  )
  {
    $this->form     = $form;
    $this->content  = $content;
    $this->loader   = $loader;
    $this->reporter = $reporter;
  }
  public function __invoke(Request $request, Response $response)
  {
    $form = $this->form;

    $form->handleRequest($request);

    if ($form->isValid()) {

      $data = $form->getData();

      $file = $data['file'];
      $file->getStream(); // Hack
      $tmpFilename = tempnam(sys_get_temp_dir(), 'AAV');
      $file->moveTo($tmpFilename);

      $loaderResults = $this->loader->load($tmpFilename);

      $reporter = $this->reporter;

      $reporter->report($loaderResults->dates,$loaderResults->officials);

      $response->getBody()->write($reporter->getContents());

      $outFilename = 'Availability-' . date('Ymd-Hi') . '.' . $reporter->getFileExtension();

      $headers = [
        'Content-Type'        => $reporter->getContentType(),
        'Content-Disposition' => sprintf('attachment; filename="%s"',$outFilename),
      ];
      foreach($headers as $name => $value) {
        $response = $response->withHeader($name,$value);
      }
      $response = $response->withStatus(201);

      return [$request,$response];
    }
    $response->getBody()->write($this->content->render($form));
    return [$request,$response];  }
}