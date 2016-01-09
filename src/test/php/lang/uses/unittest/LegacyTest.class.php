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

    $this->assertTrue(class_exists('ViaUses', false), 'Imported as global class');
    $this->assertTrue(class_exists('lang\\uses\\unittest\\ViaUses', false), 'Aliased to namespace');
    $this->assertEquals('lang.uses.unittest.ViaUses', typeof(new ViaUses())->getName(), 'Retains name');
  }

  #[@test]
  public function load_legacy_class_via_import() {
    spl_autoload_call(ViaImport::class);

    $this->assertTrue(class_exists('ViaImport', false), 'Imported as global class');
    $this->assertTrue(class_exists('lang\\uses\\unittest\\ViaImport', false), 'Aliased to namespace');
    $this->assertEquals('lang.uses.unittest.ViaImport', typeof(new ViaImport())->getName(), 'Retains name');
  }
}