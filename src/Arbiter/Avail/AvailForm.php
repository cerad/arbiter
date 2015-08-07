<?php
namespace Cerad\Component\Arbiter\Avail;

use Psr\Http\Message\ServerRequestInterface as Request;

class AvailForm
{
  protected $data = ['file' => null];

  protected $valid  = false;
  protected $posted = false;

  public function setData($data)
  {
    $this->data = $data;
  }
  public function getData()
  {
    return $this->data;
  }
  public function isValid() {
    return $this->valid;
  }
  public function handleRequest(Request $request)
  {
    if ($request->getMethod() !== 'POST') {
      return;
    }
    $this->posted = true;
    $this->valid  = true;

    $post = $request->getParsedBody();

    $this->data['file'] = $request->getUploadedFiles()['file'];

    if ($this->data['file']->getError()) {
      $this->valid = false;
    }
  }
  public function render()
  {
    return <<<TYPEOTHER
<form action="/app.php/avail" method="POST" enctype="multipart/form-data">
<label>Spreadsheet
  <input type="file" name="file" required/>
</label><br/>
  <input type="submit" value="Generate" name="generate"/>
</form>
TYPEOTHER;
  }
}