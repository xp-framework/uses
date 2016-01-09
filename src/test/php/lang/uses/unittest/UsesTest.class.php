<?php namespace lang\uses\unittest;

use lang\ClassNotFoundException;

class UsesTest extends \unittest\TestCase {
  use Assertions;

  #[@test]
  public function load_class() {
    uses('util.Date');
    $this->assertTypePresent('util.Date');
  }

  #[@test]
  public function load_classes() {
    uses('util.Objects', 'util.Filters');
    $this->assertTypePresent('util.Objects');
    $this->assertTypePresent('util.Filters');
  }

  #[@test]
  public function loading_class_twice_is_ok() {
    uses('util.Date', 'util.Date');
    $this->assertTypePresent('util.Date');
  }

  #[@test]
  public function load_existing_class() {
    uses('lang.uses.unittest.UsesTest');
    $this->assertTypePresent('lang.uses.unittest.UsesTest');
  }

  #[@test]
  public function load_class_with_import() {
    $this->assertEquals(Imports::class, Imports::$into);
  }

  #[@test, @expect(class= ClassNotFoundException::class, withMessage= '/type.does.NotExist/')]
  public function throws_when_types_do_not_exist() {
    uses('type.does.NotExist');
  }
}