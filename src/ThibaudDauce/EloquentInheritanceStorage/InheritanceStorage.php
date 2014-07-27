<?php namespace ThibaudDauce\EloquentInheritance;

class InheritanceStorage {

  /* Service status :
   * true : switch between view and storage table ON
   * false : switch between view and storage table OFF, only return storage table.
  **/
  protected $activated = true;

  const VIEW_MODE    = 'view';
  const STORAGE_MODE = 'storage';
}
