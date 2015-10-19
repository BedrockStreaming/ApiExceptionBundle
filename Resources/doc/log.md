# Logs

A log is generated for all exception matched

### Adjust log level exceptions

Three log levels are supported: `notice`, `warning` and `error`

```yaml
# ./app/config/config.yml

m6web_api_exception:
    exception:
        default:
            level: error
        exceptions:
            Acme\DemoBundle\Exception\UserTypeValidationException:
                level: warning
```

### Add a prefix to log

```yaml
# ./app/config/config.yml

m6web_api_exception:
    logger:
        prefix: "Exception Acme ==>"
```

### Change log service

Create a new logger service with implements `M6Web\Bundle\ApiExceptionBundle\Logger\LoggerInterface`

```php
<?php 

namespace Acme\DemoBundle\Logger;

use M6Web\Bundle\ApiExceptionBundle\Logger\LoggerInterface;

/**
 * Class Logger
 */
class Logger implements LoggerInterface
{
    /*...*/
}
```

Register your new logger

```yaml
# ./app/config/config.yml

services:
    acme.demo.logger:
        class: "Acme\DemoBundle\Logger\Logger"
        arguments:
            - @logger
            - "%m6web_api_exception.logger.prefix"
```

Configure `M6WebApiExceptionBundle` to use your logger

```yaml
# ./app/config/config.yml

m6web_api_exception:
    logger:
        service: "acme.demo.logger"
```

---

[Prev : Usage](https://github.com/M6Web/ApiExceptionBundle/blob/master/doc/usage.md)

[Next : Configuration](https://github.com/M6Web/ApiExceptionBundle/blob/master/doc/configuration.md)