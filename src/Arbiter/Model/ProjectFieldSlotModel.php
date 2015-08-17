<?php
namespace Cerad\Component\Model;

/* ===================================================
 * In some cases it might be nice to predefine field slots
 * In theory, a game should be assigned to a field slot instead of a field
 */
class ProjectFieldSlotModel
{
  public $keys = [
    'id',
    'project_field',   // Project field relation
    'start',
    'finish',
    'status',
  ];
}