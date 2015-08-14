<?php
namespace Cerad\Component\Arbiter;

use Cerad\Component\Dic\Dic as Dic;

class ArbiterParameters
{
  public function __construct(Dic $dic)
  {
    $dic['arbiter_db_url'] = 'mysql://impd:impd894@localhost/arbiter';
  }
}