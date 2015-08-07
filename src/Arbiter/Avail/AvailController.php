<?php
namespace Cerad\Component\Arbiter\Avail;

use Psr\Http\Message\ServerRequestInterface as Request;

use Zend\Diactoros\Stream;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\HtmlResponse;

class AvailController
{
  private $loader;
  private $reporter;

  public function __construct(AvailLoaderExcel $loader, AvailReporterExcel $reporter)
  {
    $this->loader   = $loader;
    $this->reporter = $reporter;
  }
  public function __invoke(Request $request)
  {
    $form = new AvailForm();

    $form->handleRequest($request);

    if ($form->isValid()) {

      $data = $form->getData();

      $file = $data['file'];
      $file->getStream();
      $filename = tempnam(sys_get_temp_dir(), 'AAV');
      $file->moveTo($filename);

      $loaderResults = $this->loader->load($filename);

      $reporter = $this->reporter;

      $reporter->report($loaderResults->dates,$loaderResults->officials);

      //$filename .= $reporter->getFileExtension();

      $reporter->save($filename);

      $outFilename = 'Availability-' . date('Ymd-Hi') . '.' . $reporter->getFileExtension();

      $headers = [
        'Content-Type'        => $reporter->getContentType(),
        'Content-Disposition' => sprintf('attachment; filename="%s"',$outFilename),
      ];
      $stream = new Stream($filename);
      return new Response($stream,201,$headers);
    }
    return new HtmlResponse($form->render());
  }
}