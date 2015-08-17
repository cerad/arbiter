<?php
namespace Cerad\Component\Model;

/* ===================================================
 * Possible that this should be physical field only
 * Also want ProjectSite relation of some sort
 */
class ProjectFieldModel
{
  public $keys = [
    'id',
    'field',   // Physical field relation
    'project', // Project relation
    'name',
    'title',
    'status',
  ];
}