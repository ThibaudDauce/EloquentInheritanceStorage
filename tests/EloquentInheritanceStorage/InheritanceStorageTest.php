<?php
use ThibaudDauce\EloquentInheritanceStorage\ParentTrait;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Foundation\Testing\TestCase;
use ThibaudDauce\EloquentInheritanceStorage\InheritanceStorage;
use ThibaudDauce\EloquentInheritanceStorage\Facades\InheritanceStorage as FacadeInheritanceStorage;

class InheritanceStorageTest extends TestCase {

  public $warrior;
  public $wizard;
  public $character;
  public $characterCustomStorage;

  /**
   * Creates the application.
   *
   * @return \Symfony\Component\HttpKernel\HttpKernelInterface
   */
  public function createApplication()
  {
    $unitTesting = true;

    $testEnvironment = 'testing';

    return require __DIR__.'/../../../../../bootstrap/start.php';
  }

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

  public function testInheritanceStorageActivation()
  {
    $this->assertTrue(InheritanceStorage::$activated);
    FacadeInheritanceStorage::desactivate();
    $this->assertFalse(InheritanceStorage::$activated);
    FacadeInheritanceStorage::activate();
    $this->assertTrue(InheritanceStorage::$activated);
  }
}
