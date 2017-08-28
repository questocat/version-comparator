## version-matcher

Compares two version number strings based on [Semantic Versioning 2.0.0](http://semver.org)

## Installation

Using [Composer](https://getcomposer.org) to add the package to your project's dependencies:

```php
composer require emanci/version-compare
```

## Usage
```php
// using compare method

$semVerManager = new SemVerManager();
$semVerManager->compare('2.9.0', '2.9.6', '<');                           // true
$semVerManager->compare('5.1.0', '5.1.0-alpha', '>');                     // true
$semVerManager->compare('2.0.1-alpha.2', '2.0.1-alpha.1', '<');           // false
$semVerManager->compare('2.0.1-alpha.2', '2.0.1-alpha.1', '>');           // true
$semVerManager->compare('2.0.1-alpha.2', '2.0.1-alpha.1', '!=');          // true
$semVerManager->compare('1.0.0-alpha.1', '1.0.0-alpha.beta', '<');        // true
$semVerManager->compare('2.0.1-alpha-abc.2', '2.0.1-alpha-abc.1', '>');   // true
$semVerManager->compare('1.0.0-alpha', '1.0.0-beta', '<');                // true
$semVerManager->compare('0.0.0+0', '0.0.0+1', '=');                       // true
$semVerManager->compare('1.0.0-alpha+001', '1.0.0+20130313144700', '<');  // true
$semVerManager->compare('1.0.0-rc.1+build.1', '1.0.0-rc.1', '<');         // false
$semVerManager->compare('1.0.0-rc.1+build.1', '1.0.0-rc.1', '=');         // true
$semVerManager->compare('1.0.0-rc.2+build.1', '1.0.0-rc.1', '<');         // fasle
$semVerManager->compare('1.0.0-rc.2+build.1', '1.0.0-rc.1', '>=');        // true

// other examples
$semVerManager->compare('1.0.0-alpha', '1.0.0-alpha.1', '<');
$semVerManager->compare('1.0.0-alpha.1', '1.0.0-alpha.beta', '<');
$semVerManager->compare('1.0.0-alpha.beta', '1.0.0-beta', '<');
$semVerManager->compare('1.0.0-beta', '1.0.0-beta.2', '<');
$semVerManager->compare('1.0.0-beta.2', '1.0.0-beta.11', '<');
$semVerManager->compare('1.0.0-beta.11', '1.0.0-rc.1', '<');
$semVerManager->compare('1.0.0-rc.1', '1.0.0', '<');

// conclusion
// 1.0.0-alpha < 1.0.0-alpha.1 < 1.0.0-alpha.beta < 1.0.0-beta < 1.0.0-beta.2 < 1.0.0-beta.11 < 1.0.0- rc.1 < 1.0.0


// using compareTo method

$semVerManager = new SemVerManager('2.9.0');
$semVerManager->compareTo('2.9.6', '<');        // true
$semVerManager->compareTo('2.8.9', '>');        // true
$semVerManager->compareTo('2.8.9-alpha', '>');  // true
```

## Reference

* [semver](http://semver.org)
* [version_compare](http://php.net/manual/en/function.version-compare.php)
* [php-src](https://github.com/php/php-src)

## License

Licensed under the [MIT license](https://github.com/emanci/version-compare/blob/master/LICENSE).
