Uses BC
=======

[![Build Status on TravisCI](https://secure.travis-ci.org/xp-framework/uses.svg)](http://travis-ci.org/xp-framework/uses)
[![XP Framework Module](https://raw.githubusercontent.com/xp-framework/web/master/static/xp-framework-badge.png)](https://github.com/xp-framework/core)
[![BSD Licence](https://raw.githubusercontent.com/xp-framework/web/master/static/licence-bsd.png)](https://github.com/xp-framework/core/blob/master/LICENCE.md)
[![Required PHP 5.5+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-5_5plus.png)](http://php.net/)
[![Supports PHP 7.0+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-7_0plus.png)](http://php.net/)
[![Supports HHVM 3.4+](https://raw.githubusercontent.com/xp-framework/web/master/static/hhvm-3_4plus.png)](http://hhvm.com/)
[![Latest Stable Version](https://poser.pugx.org/xp-framework/uses/version.png)](https://packagist.org/packages/xp-framework/uses)

Backward compatibility for loading classes with `uses()` with XP 6.4.0+


Motivation
----------
**In [XP 6.4.0](https://github.com/xp-framework/core/releases/tag/v6.4.0), support for loading classes via `uses()` was dropped as part of the framework's path to PHP 7.0.** Unfortunately, not everyone may be able to adapt their entire codebase in a timely manner. However, they still might want to be able to use newer versions of the framework, which include bugfixes they might need. *This library can help during this transient state.*


Background
----------
Before the XP Framework was completely migrated to PHP namespaces, classes where declared as follows:

```php
<?php
  uses('util.Filter');

  class Filters extends Object implements Filter {
    // ...
  }
?>
```

Notes:

* Classes exist in the global namespace, their names are inferred by the `xp::$cn` map which stores the association long name => literal.
* The `Object` can be referred to by its short name. This is because it belongs to a list of omnipresent classes.
* The `Filter` interface can be referred to by its short name. This is because it was loaded explicitely above, and happens regardless of whether it is declared namespaced or not.


What this library does
----------------------
This library:

* **Adds a `uses()` function**. It takes care of loading classes, creating short name aliases for namespaced classes and handling imports.
* **Injects a class loader** in the class loading chain which adds support for legacy class declarations. It ensures class name / literal mappings are correctly registered.

If successfully installed, you will see the `UsesCL` line appear:

```sh
$ xp -v
XP 6.9.3-dev { PHP 7.0.0 & ZE 3.0.0 } @ Windows NT SLATE 10.0 build 10586 (Windows 10) i586
Copyright (c) 2001-2016 the XP group
UsesCL(cached: 0)
FileSystemCL<~/devel/xp/core/src/main/php>
# ...
```