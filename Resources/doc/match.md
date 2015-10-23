# Match exceptions

By default, `M6WebApiExceptionBundle` transform all exceptions to json reponse, configured in yaml or not. If you want only tranform exceptions provided by M6WebApiExceptionBundle, just configure `match_all` to `false`
```yaml
# ./app/config/config.yml

m6web_api_exception:
    exception:
        match_all: false
```

Now, only exceptions which implement `M6Web\Bundle\ApiExceptionBundle\Exception\Interfaces\ExceptionInterface` will turn into json response with this bundle

**Exceptions not provided by `M6WebApiExceptionBundle` can't be configure in yaml with bundle. Parameters used (message, code, status, etc...) are those defined in the construction of the exception. Missing parameters will be complemented by default exception configuration of this bundle.**

Example :

```php
<?php 

namespace Acme\DemoBundle\Manager;

use Acme\DemoBundle\Entity\User;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class UserManager
 */
class UserManager
{
    /*...*/

    if (!$manager) {
        throw new \Exception(19, 'manager missing');
    }
    
    if (!$user) {
        throw new HttpException(404, 'user not found', 96);
    }
    
    /*...*/
}
```

Result

```json
{
    "error": {
        "status": 500,
        "code": 19,
        "message": "manager missing"
    }
}
```
*Status is equal to `500`, Use exception default configuration to define because `\Exception` don't have status code*

```json
{
    "error": {
        "status": 404,
        "code": 96,
        "message": "user not found"
    }
}
```

---

[Prev : Errors](https://github.com/M6Web/ApiExceptionBundle/blob/master/doc/errors.md)

[Next : Log](https://github.com/M6Web/ApiExceptionBundle/blob/master/doc/log.md)