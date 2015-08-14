<?php
namespace Cerad\Component\Arbiter;

use Cerad\Component\Dic\Dic as Dic;

class ArbiterParametersDist
{
  public function __construct(Dic $dic)
  {
    $dic['arbiter_db_url'] = 'mysql://USER:PASS@HOST/arbiter';
  }
}