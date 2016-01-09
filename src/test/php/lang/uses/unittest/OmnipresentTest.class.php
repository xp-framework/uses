<?php namespace lang\uses\unittest;

use unittest\actions\RuntimeVersion;

class OmnipresentTest extends \unittest\TestCase {
  use Assertions;

  #[@test]
  public function generic_interface_is_present_and_aliased() {
    $this->assertTypePresent('lang.Generic', 'interface_exists');
  }

  #[@test, @values([
  #  'lang.Object',
  #  'lang.StackTraceElement',
  #  'lang.XPException',
  #  'lang.Type',
  #  'lang.XPClass', 
  #  'lang.reflect.Routine',
  #  'lang.reflect.Parameter',
  #  'lang.reflect.Method',
  #  'lang.reflect.TargetInvocationException',
  #  'lang.reflect.Field',
  #  'lang.reflect.Constructor',
  #  'lang.reflect.Modifiers',
  #  'lang.reflect.Package',
  #  'lang.FileSystemClassLoader',
  #  'lang.DynamicClassLoader',
  #  'lang.ClassLoader',
  #  'lang.archive.ArchiveClassLoader', 
  #  'lang.NullPointerException',
  #  'lang.IllegalAccessException',
  #  'lang.IllegalArgumentException',
  #  'lang.IllegalStateException',
  #  'lang.FormatException',
  #  'lang.ClassNotFoundException'
  #])]
  public function class_is_present_and_aliased($type) {
    $this->assertTypePresent($type, 'class_exists');
  }

  #[@test, @action(new RuntimeVersion('<7.0.0')), @values(['lang.Error','lang.Throwable'])]
  public function throwable_and_error_class_are_present($type) {
    $this->assertTypePresent($type, 'class_exists');
  }
}