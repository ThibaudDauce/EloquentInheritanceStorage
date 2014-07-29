<?php
use ThibaudDauce\EloquentInheritanceStorage\InheritanceStorage;

class InheritanceStorageTest extends PHPUnit_Framework_TestCase {

  public $character;
  public $inheritanceStorage;

  public function setUp()
  {
    parent::setUp();

    // Build some objects for our tests
    $this->character          = new Character;
    $this->inheritanceStorage = new InheritanceStorage;
  }

  public function tearDown()
  {
    //
  }

  public function testInheritanceStorageActivation()
  {
    $this->assertTrue(InheritanceStorage::$activated);
    $this->inheritanceStorage->desactivate();
    $this->assertFalse(InheritanceStorage::$activated);
    $this->inheritanceStorage->activate();
    $this->assertTrue(InheritanceStorage::$activated);
  }

  public function testActionOnStorage()
  {
    $storageTable = $this->inheritanceStorage->actionOnStorage(function() {
      $character = new Character;
      return $character->getTable();
    });
    $this->assertEquals('characters_storage', $storageTable);

    $character = new Character;
    $notStorageTable = $character->getTable();
    $this->assertNotEquals('characters_storage', $notStorageTable);
  }
}
