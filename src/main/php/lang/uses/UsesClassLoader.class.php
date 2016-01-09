<?php namespace lang\uses;

use lang\XPClass;
use lang\ClassLoader;
use lang\ClassNotFoundException;
use lang\ClassFormatException;
use lang\ClassLinkageException;
use lang\ClassDependencyException;
use lang\reflect\TargetInvocationException;

/**
 * Intercept regular class loading
 */
class UsesClassLoader extends \lang\Object implements \lang\IClassLoader {
  private $location= [];

  /**
   * Returns whether a type exists
   *
   * @param  string $literal
   * @return bool
   */
  public static function exists($literal) {
    return class_exists($literal, false) || interface_exists($literal, false) || trait_exists($literal, false);
  }

  /**
   * Locate a class, and cache this.
   *
   * @param  string $class
   * @return lang.IClassLoader or NULL if nothing can be found
   */
  private function locate($class) {
    if (!isset($this->location[$class])) {
      $this->location[$class]= null;
      foreach (ClassLoader::getLoaders() as $loader) {
        if ($loader !== $this && $loader->providesClass($class)) {
          $this->location[$class]= $loader;
          break;
        }
      }
    }
    return $this->location[$class];
  }

  /**
   * Checks whether this class loader provides a given uri
   *
   * @param  string $uri
   * @return bool
   */
  public function providesUri($uri) {
    return false;
  }

  /**
   * Checks whether this class loader provides a given class
   *
   * @param  string $class
   * @return bool
   */
  public function providesClass($class) {
    return null !== $this->locate($class);
  }

  /**
   * Checks whether this class loader provides a given resource
   *
   * @param  string $filename
   * @return bool
   */
  public function providesResource($filename) {
    return false;
  }

  /**
   * Checks whether this class loader provides a given package
   *
   * @param  string $package
   * @return bool
   */
  public function providesPackage($package) {
    return false;
  }

  /**
   * Returns a given package's contents
   *
   * @param  string $package
   * @return string[]
   */
  public function packageContents($package) {
    return [];
  }

  /**
   * Loads a class
   *
   * @param  string $class
   * @return lang.XPClass
   * @throws lang.ClassLoadingException
   */
  public function loadClass($class) {
    return new XPClass($this->loadClass0($class));
  }

  /**
   * Loads a class. Uses and then clears location cache.
   *
   * @param  string $class
   * @return string
   * @throws lang.ClassLoadingException
   */
  public function loadClass0($class) {
    if (isset(\xp::$cl[$class])) return literal($class);

    if (null === ($loader= $this->locate($class))) {
      unset($this->location[$class]);
      throw new ClassNotFoundException($class);
    }

    $package= null;
    \xp::$cl[$class]= nameof($loader).'://'.$loader->path;
    \xp::$cll++;
    try {
      $r= include(typeof($loader)->getMethod('classUri')->setAccessible(true)->invoke($loader, [$class]));
    } catch (TargetInvocationException $e) {
      unset(\xp::$cl[$class]);
      unset($this->location[$class]);
      \xp::$cll--;

      $decl= null;
      if (null === $package) {
        $decl= substr($class, (false === ($p= strrpos($class, '.')) ? 0 : $p + 1));
      } else {
        $decl= strtr($class, '.', '·');
      }

      // If class was declared, but loading threw an exception it means
      // a "soft" dependency, one that is only required at runtime, was
      // not loaded, the class itself has been declared.
      if (self::exists($decl)) {
        throw new ClassDependencyException($class, [$loader], $e->getCause());
      }

      // If otherwise, a "hard" dependency could not be loaded, eg. the
      // base class or a required interface and thus the class could not
      // be declared.
      throw new ClassLinkageException($class, [$loader], $e->getCause());
    }

    unset($this->location[$class]);

    \xp::$cll--;
    if (false === $r) {
      unset(\xp::$cl[$class]);
      $e= new ClassNotFoundException($class, [$loader]);
      \xp::gc(__FILE__);
      throw $e;
    }

    // Register class name / literal mapping
    if (false === ($p= strrpos($class, '.'))) {
      $name= $class;
      \xp::$sn[$class]= $name;
    } else if (null !== $package) {
      $name= strtr($class, '.', '·');
      class_alias($name, strtr($class, '.', '\\'));
      \xp::$sn[$class]= $name;
    } else if (($ns= strtr($class, '.', '\\')) && self::exists($ns)) {
      $name= $ns;
    } else if (($cl= substr($class, $p+ 1)) && self::exists($cl)) {
      $name= $cl;
      class_alias($name, strtr($class, '.', '\\'));
      \xp::$sn[$class]= $name;
    } else {
      unset(\xp::$cl[$class]);
      throw new ClassFormatException('Class "'.$class.'" not declared in loaded file');
    }

    method_exists($name, '__static') && \xp::$cli[]= [$name, '__static'];
    if (0 === \xp::$cll) {
      $invocations= \xp::$cli;
      \xp::$cli= [];
      foreach ($invocations as $inv) call_user_func($inv, $name);
    }

    return $name;
  }

  /**
   * Gets a resource
   *
   * @param  string $string name
   * @return string
   * @throws lang.ElementNotFoundException
   */
  public function getResource($string) {
    throw new ElementNotFoundException($string);
  }

  /**
   * Gets a resource as a stream
   *
   * @param  string $string name
   * @return io.Stream
   * @throws lang.ElementNotFoundException
   */
  public function getResourceAsStream($string) {
    throw new ElementNotFoundException($string);
  }

  /**
   * Get unique identifier for this class loader
   *
   * @return string
   */
  public function instanceId() {
    return 'uses';
  }

  /**
   * Fetch instance of classloader by path
   *
   * @param   string path the identifier
   * @return  lang.IClassLoader
   */
  public static function instanceFor($path) {
    static $pool= [];

    if (!isset($pool[$path])) {
      $pool[$path]= new self($path);
    }
    return $pool[$path];
  }

  /**
   * Gets a string representation
   *
   * @return string
   */
  public function toString() {
    return 'UsesCL(cached: '.sizeof($this->location).')';
  }
}
