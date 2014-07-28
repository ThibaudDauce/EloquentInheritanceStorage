<?php namespace ThibaudDauce\EloquentInheritanceStorage;

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
   * Delete the model from the database.
   *
   * @override Illuminate\Database\Eloquent\Model
   * @return bool|null
   * @throws \Exception
   */
  public function delete()
  {
    $previousMode = $this->setInheritanceStorageMode(InheritanceStorage::STORAGE_MODE);

    parent::delete();

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
    if ($this->isInheritancechild() OR (InheritanceStorage::$activated AND $this->getInheritanceStorageMode() == InheritanceStorage::VIEW_MODE))
      return parent::getTable();
    else
      return $this->getInheritanceStorage();
  }

  /**
   * Create a new model instance that is existing.
   *
   * @override Illuminate\Database\Eloquent\Model
   * @param  array  $attributes
   * @return \Illuminate\Database\Eloquent\Model|static
   */
  public function newFromBuilder($attributes = array())
  {
    if (!isset($attributes->class_name))
      return parent::newFromBuilder($attributes);

    $class = $attributes->class_name;
    $instance = new $class;

    $instance->setRawAttributes((array) $attributes, true);

    return $instance;
  }

  /**
   * Get the storage table associated with the model.
   *
   * @return string
   */
  public function getInheritanceStorage() {

    if (isset($this->inheritanceStorageName))
      return $this->inheritanceStorageName;
    else
      return parent::getTable() . $this->storageSeparator . $this->storageSuffix;
  }

  /**
   * Get the current mode associated with the model.
   *
   * @return InheritanceStorage::VIEW_MODE or InheritanceStorage::STORAGE_MODE
   */
  public function getInheritanceStorageMode() {

    return $this->inheritanceStorageMode;
  }

  /**
   * Set the mode of the model.
   * @throws \InvalidArgumentException
   * @return $previousMode (InheritanceStorage::VIEW_MODE or InheritanceStorage::STORAGE_MODE)
   */
  public function setInheritanceStorageMode($mode) {

    if (!in_array($mode, [InheritanceStorage::VIEW_MODE, InheritanceStorage::STORAGE_MODE]))
      throw new \InvalidArgumentException("Can't set InheritanceStorageMode to ".$mode." because it is not a valid mode.");

    $previousMode = $this->getInheritanceStorageMode();

    $this->inheritanceStorageMode = $mode;

    return $previousMode;
  }

  /**
   * Check if the model is a parent or a child.
   *
   * @return boolean
   */
  public function isInheritanceChild() {

    return get_class() !== get_class($this);
  }
}
