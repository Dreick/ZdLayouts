ZdLayouts
=================

Installation
-----------------

### With composer

```php
php composer.phar require "dreick/zd-layouts": "dev-master"
```
Then add ZdLayouts to your config/application.config.php

Usage
-----------------
```php
array(
    'module_layouts' => array(
        __NAMESPACE__ => 'layout/some-layout',
        'routes' => array(
            'some' => 'layout/some-layout',
            'some/*' => 'layout/some-other-layout',
        ),
    ),
);
```
