<?php namespace lang\uses\unittest;

/**
 * Fixture for UsesTest::load_class_with_import(). Imported by the
 * Imports class, recording this in its public static `into` member.
 */
class Imported {
  static function __import($scope) {
    Imports::$into= $scope;
  }
}