<?php namespace ThibaudDauce\EloquentInheritanceStorage\Facades;

use Illuminate\Support\Facades\Facade;

class InheritanceStorage extends Facade {

  protected static function getFacadeAccessor() { return 'eloquent-inheritance-storage'; }
}
