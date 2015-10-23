# Installation

Require the bundle in your composer.json file :

```json
{
    "require": {
        "m6web/api-exception-bundle": "~1.0.0",
    }
}
```

Register the bundle in your kernel :

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        new M6Web\Bundle\ApiExceptionBundle\M6WebApiExceptionBundle(),
    );
}
```

Then install the bundle :

```shell
$ composer update m6web/api-exception-bundle
```

---

[Next : Usage](https://github.com/M6Web/ApiExceptionBundle/blob/master/doc/usage.md)