<?php
namespace Cerad\Component\Dic;

use Pimple\Container as PimpleContainer;
use Interop\Container\ContainerInterface as ContainerInterface;

class Dic extends PimpleContainer implements ContainerInterface
{
  /**
   * {@inheritdoc}
   * 
   * TODO: Add interop exception
   */
  public function get($id)
  {
    return $this->offsetGet($id);
  }
  public function has($id)
  {
    return $this->offsetExists($id);
  }
}