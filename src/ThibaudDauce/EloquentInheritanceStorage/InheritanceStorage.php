<?php namespace ThibaudDauce\EloquentInheritanceStorage;

use Closure;

class InheritanceStorage {

  /* Service status :
   * true : switch between view and storage table ON
   * false : switch between view and storage table OFF, only return storage table.
  **/
  static $activated = true;

  const VIEW_MODE    = 'view';
  const STORAGE_MODE = 'storage';

  /*
   * Getters for const values
  **/
  public function VIEW_MODE() {
    return self::VIEW_MODE;
  }

  public function STORAGE_MODE() {
    return self::STORAGE_MODE;
  }

  public function activate() {
    self::$activated = true;
  }

  public function desactivate() {
    self::$activated = false;
  }

  public function actionOnStorage(Closure $action) {

    self::desactivate();

    $data = $action();

    self::activate();

    return $data;
  }
}
