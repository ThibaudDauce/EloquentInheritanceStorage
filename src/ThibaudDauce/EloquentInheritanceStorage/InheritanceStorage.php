<?php namespace ThibaudDauce\EloquentInheritanceStorage;

class InheritanceStorage {

  /* Service status :
   * true : switch between view and storage table ON
   * false : switch between view and storage table OFF, only return storage table.
  **/
  public $activated = true;

  const VIEW_MODE    = 'view';
  const STORAGE_MODE = 'storage';

  /*
   * Getters for const values 
  **/
  public function VIEW_MODE() {
    return static::VIEW_MODE;
  }

  public function STORAGE_MODE() {
    return static::STORAGE_MODE;
  }
}
