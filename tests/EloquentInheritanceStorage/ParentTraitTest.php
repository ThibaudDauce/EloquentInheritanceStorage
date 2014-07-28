<?php
use ThibaudDauce\EloquentInheritanceStorage\ParentTrait;
use Illuminate\Database\Eloquent\Model as Eloquent;
use ThibaudDauce\EloquentInheritanceStorage\InheritanceStorage;

class ParentTraitTest extends PHPUnit_Framework_TestCase {
  
  public $warrior;
  public $wizard;
  public $character;
  public $characterCustomStorage;

  public function setUp()
  {
    parent::setUp();

    // Build some objects for our tests
    $this->character              = new Character;
    $this->warrior                = new Warrior;
    $this->wizard                 = new Wizard;
    $this->characterCustomStorage = new CharacterCustomStorage;
  }

  public function tearDown()
  {
    //
  }

  public function testIsInheritanceChild()
  {
    $this->assertFalse($this->character->isInheritanceChild());
    $this->assertFalse($this->characterCustomStorage->isInheritanceChild());
    $this->assertTrue($this->warrior->isInheritanceChild());
    $this->assertTrue($this->wizard->isInheritanceChild());
  }

  public function testGetTable()
  {
    // By default we should hit the view
    $this->assertEquals('characters', $this->character->getTable());
    
    // Check that if we are in storage mode we will have the storage table
    $this->character->setInheritanceStorageMode(InheritanceStorage::STORAGE_MODE);
    $this->assertEquals('characters_storage', $this->character->getTable());
    
    // Switch back to the view mode
    $this->character->setInheritanceStorageMode(InheritanceStorage::VIEW_MODE);
    $this->assertEquals('characters', $this->character->getTable());
    $this->assertEquals('warriors', $this->warrior->getTable());
    $this->assertEquals('wizards', $this->wizard->getTable());
  }

  public function testGetInheritanceStorage()
  {
    $this->assertEquals('characters_storage', $this->character->getInheritanceStorage());
    $this->assertEquals('custom-storage-name', $this->characterCustomStorage->getInheritanceStorage());
  }

  public function testNewFromBuilder()
  {
    // Test with the parent class without a class_name
    $character = new Character;
    $characterAttributes = ['name' => 'Antoine'];
    $character = $character->newFromBuilder($characterAttributes);

    $this->assertTrue($character instanceof Character);
    $this->assertFalse($character instanceof Warrior);
    $this->assertFalse($character instanceof Wizard);
    $this->assertEquals($characterAttributes['name'], $character->getAttribute('name'));

    // Test with a child class
    $warrior = new Warrior;
    $warriorAttributes = ['class_name' => 'Warrior', 'name' => 'Antoine', 'rage' => 42];
    $warrior = $warrior->newFromBuilder($warriorAttributes);

    $this->assertTrue($warrior instanceof Warrior);
    $this->assertEquals($warriorAttributes['name'], $warrior->getAttribute('name'));
    $this->assertEquals($warriorAttributes['rage'], $warrior->getAttribute('rage'));
  }

  public function testInheritanceStorageMode()
  {
    $previousMode = $this->character->getInheritanceStorageMode();
    $result = $this->character->setInheritanceStorageMode(InheritanceStorage::VIEW_MODE);

    // Should return the previous value after setting
    $this->assertEquals($previousMode, $result);
    // Should have set the new value
    $this->assertEquals(InheritanceStorage::VIEW_MODE, $this->character->getInheritanceStorageMode());
  }

  /**
   * @expectedException \InvalidArgumentException
  */
  public function testWrongSetInheritanceStorageMode()
  {
    $this->character->setInheritanceStorageMode('bar');
  }
}

// The parent class
class Character extends Eloquent {

  use ParentTrait;

  protected $table = 'characters';
  protected $primaryKey = 'name';
}

class CharacterCustomStorage extends Eloquent {

  use ParentTrait;

  protected $table = 'characters';
  protected $primaryKey = 'name';
  protected $inheritanceStorageName = 'custom-storage-name';
}

// Child classes
class Warrior extends Character {

  protected $table = 'warriors';
}

class Wizard extends Character {

  protected $table = 'wizards';
}