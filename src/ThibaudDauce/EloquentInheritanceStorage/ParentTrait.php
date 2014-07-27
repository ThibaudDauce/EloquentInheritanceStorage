<?php namespace ThibaudDauce\EloquentInheritanceStorage;

use ThibaudDauce\EloquentInheritanceStorage\Facades\InheritanceStorage as InheritanceStorageFacade;
use ThibaudDauce\EloquentInheritanceStorage\InheritanceStorage as InheritanceStorage;

trait ParentTrait {

  // Default value : get models from view
  private $inheritanceStorageMode = InheritanceStorage::VIEW_MODE;

  // Default view name : "models_storage"
  private $storageSuffix = 'storage';
  private $storageSeparator = '_';

  /**
   * Save the model to the database.
   *
   * @override Illuminate\Database\Eloquent\Model
   * @param  array  $options
   * @return bool
   */
  public function save(array $options = array())
  {
    $previousMode = $this->setMode(Inheritance::SET_MODE);

    parent::save($options);

    $this->setMode($previousMode);
  }

  /**
   * Get the table or view associated with the model.
   *
   * @override Illuminate\Database\Eloquent\Model
   * @return string
   */
  public function getTable()
  {
    if ($this->getMode() == Inheritance::GET_MODE)
      return parent::getTable();
    else
      return $this->getStorage();
  }
}
