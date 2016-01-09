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


Example
-------
Before the XP Framework was completely migrated to PHP namespaces, classes where declared as follows:

```php
<?php
  uses('util.Filter');

  class Filters extends Object implements Filter {
    // ...
  }
?>

Notes:

* Classes exist in the global namespace, their names are inferred by the `xp::$cn` map which stores the association long name => literal.
* The `Object` can be referred to by its short name. This is because it belongs to a list of omnipresent classes.
* The `Filter` interface can be referred to by its short name. This is because it was loaded explicitely above.