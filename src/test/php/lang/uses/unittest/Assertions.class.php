<?php namespace lang\uses\unittest;

trait Assertions {

  /**
   * Asserts a given type has been loaded and aliased
   *
   * @param  string $type
   * @param  callable $exists
   * @return void
   * @throws unittest.AssertionFailedError
   */
  private function assertTypePresent($type, $exists= 'class_exists') {
    $literal= strtr($type, '.', '\\');
    $alias= substr($type, strrpos($type, '.') + 1);

    $this->assertTrue($exists($literal, false), 'Imported as '.$literal);
    $this->assertEquals($literal, (new \ReflectionClass($alias))->getName(), 'Aliased as '.$alias);
  }
}