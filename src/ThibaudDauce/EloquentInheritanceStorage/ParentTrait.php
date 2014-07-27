<?php namespace ThibaudDauce\EloquentInheritanceStorage;

use ThibaudDauce\EloquentInheritanceStorage\Facades\InheritanceStorage as InheritanceStorageFacade;
use ThibaudDauce\EloquentInheritanceStorage\InheritanceStorage as InheritanceStorage;

trait ParentTrait {

  // Default value : get models from view
  protected $inheritanceStorageMode = InheritanceStorage::VIEW_MODE;

  // Default view name : "models_storage"
  protected $storageSuffix = 'storage';
  protected $storageSeparator = '_';

  /**
   * Save the model to the database.
   *
   * @override Illuminate\Database\Eloquent\Model
   * @param  array  $options
   * @return bool
   */
  public function save(array $options = array())
  {
    $previousMode = $this->setInheritanceStorageMode(InheritanceStorage::STORAGE_MODE);

    parent::save($options);

    $this->setInheritanceStorageMode($previousMode);
  }

  /**
   * Perform the actual delete query on this model instance.
   *
   * @override Illuminate\Database\Eloquent\Model
   * @return void
   */
  protected function performDeleteOnModel()
  {
    $previousMode = $this->setInheritanceStorageMode(InheritanceStorage::STORAGE_MODE);

    parent::performDeleteOnModel();

    $this->setInheritanceStorageMode($previousMode);
  }

  /**
   * Get the table or view associated with the model.
   *
   * @override Illuminate\Database\Eloquent\Model
   * @return string
   */
  public function getTable()
  {
    if ($this->getInheritanceStorageMode() == InheritanceStorage::VIEW_MODE)
      return parent::getTable();
    else
      return $this->getInheritanceStorage();
  }

  /**
   * Get the storage table associated with the model.
   *
   * @return string
   */
  public function getInheritanceStorage() {

    return parent::getTable() . $this->storageSeparator . $this->storageSuffix;
  }

  /**
   * Get the current mode associated with the model.
   *
   * @return InheritanceStorage::VIEW_MODE() or InheritanceStorage::STORAGE_MODE()
   */
  public function getInheritanceStorageMode() {

    return $this->inheritanceStorageMode;
  }

  /**
   * Set the mode of the model.
   *
   * @return $previousMode (InheritanceStorage::VIEW_MODE() or InheritanceStorage::STORAGE_MODE())
   */
  public function setInheritanceStorageMode($mode) {

    $previousMode = $this->getInheritanceStorageMode();

    $this->inheritanceStorageMode = $mode;

    return $previousMode;
  }
}
