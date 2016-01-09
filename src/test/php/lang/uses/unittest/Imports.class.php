<?php namespace lang\uses\unittest;

uses('lang.uses.unittest.Imported');

/**
 * Fixture for UsesTest::load_class_with_import(). Ensures the Imported
 * class is imported into this class' scope.
 */
class Imports {
  public static $into= null;
}