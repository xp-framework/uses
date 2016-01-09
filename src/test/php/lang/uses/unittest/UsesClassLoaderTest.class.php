<?php namespace lang\uses\unittest;

use lang\uses\UsesClassLoader;

class UsesClassLoaderTest extends \unittest\TestCase {
  private $fixture;

  /** @return void */
  public function setUp() {
    $this->fixture= new UsesClassLoader();
  }

  #[@test]
  public function provides_this_class() {
    $this->assertTrue($this->fixture->providesClass(nameof($this)));
  }

  #[@test]
  public function provides_this_uri() {
    $this->assertTrue($this->fixture->providesUri(__FILE__));
  }

  #[@test]
  public function cache_initially_empty() {
    $this->assertEquals('UsesCL(cached: 0)', $this->fixture->toString());
  }

  #[@test]
  public function cache_filled_after_providesClass_is_called() {
    $this->fixture->providesClass(nameof($this));
    $this->assertEquals('UsesCL(cached: 1)', $this->fixture->toString());
  }

  #[@test]
  public function cache_emptied_after_loadClass_is_called() {
    $this->fixture->providesClass(nameof($this));
    $this->fixture->loadClass(nameof($this));
    $this->assertEquals('UsesCL(cached: 0)', $this->fixture->toString());
  }
}