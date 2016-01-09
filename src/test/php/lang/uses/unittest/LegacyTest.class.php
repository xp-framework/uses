<?php namespace lang\uses\unittest;

use lang\ClassLoader;
use io\Path;

class LegacyTest extends \unittest\TestCase {
  private static $loader;

  #[@beforeClass]
  public static function setupLoader() {
    self::$loader= ClassLoader::registerPath(new Path(__DIR__, 'legacy.xar'));
  }

  #[@afterClass]
  public static function removeLoader() {
    ClassLoader::removeLoader(self::$loader);
  }

  #[@test]
  public function load_legacy_class_via_uses() {
    uses('lang.uses.unittest.ViaUses');

    $literal= 'ViaUses';
    $this->assertTrue(class_exists($literal, false), 'Imported as global class');
    $this->assertTrue(class_exists('lang\\uses\\unittest\\ViaUses', false), 'Aliased to namespace');
    $this->assertEquals('lang.uses.unittest.ViaUses', typeof(new $literal())->getName(), 'Retains name');
  }

  #[@test]
  public function load_legacy_class_via_import() {
    spl_autoload_call(ViaImport::class);

    $literal= 'ViaImport';
    $this->assertTrue(class_exists($literal, false), 'Imported as global class');
    $this->assertTrue(class_exists('lang\\uses\\unittest\\ViaImport', false), 'Aliased to namespace');
    $this->assertEquals('lang.uses.unittest.ViaImport', typeof(new $literal())->getName(), 'Retains name');
  }

  #[@test]
  public function load_qualified_legacy_class() {
    uses('lang.uses.unittest.Qualified');

    $literal= "lang\xb7uses\xb7unittest\xb7Qualified";
    $this->assertTrue(class_exists($literal, false), 'Imported as qualified class');
    $this->assertTrue(class_exists('lang\\uses\\unittest\\Qualified', false), 'Aliased to namespace');
    $this->assertEquals('lang.uses.unittest.Qualified', typeof(new $literal())->getName(), 'Retains name');
  }
}