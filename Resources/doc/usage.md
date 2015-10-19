# Usage

## Create new exception in your API

Create a new class exception extends an exception from this bundle

```php
<?php 

namespace Acme\DemoBundle\Exception;

use M6Web\Bundle\ApiExceptionBundle\Exception\HttpException;

/**
 * Class UserNotFoundException
 */
class UserNotFoundException extends HttpException
{
}
```

Configure your new exception

```yaml
# ./app/config/config.yml

m6web_api_exception:
    exception:
        exceptions:
            Acme\DemoBundle\Exception\UserNotFoundException:
                status: 404 # Used for exceptions implements HttpExceptionInterface
                code: 5286 # Create an unique code for this exception in your API
                message: "user not found"
```
*The configuration will impact only the exceptions that implements `M6Web\Bundle\ApiExceptionBundle\Exception\Interfaces\ExceptionInterface`*

Use your new exception

```php
<?php 

namespace Acme\DemoBundle\Manager;

use Acme\DemoBundle\Entity\User;
use Acme\DemoBundle\Exception\UserNotFoundException;

/**
 * Class UserManager
 */
class UserManager
{
    /*...*/

    /**
     * @param integer $id
     *
     * @throws UserNotFoundException
     *
     * @return User $user
     */
    public function getUser($id)
    {
        $user = /*...*/;
        
        if (!$user) {
            throw new UserNotFoundException;
        }

        return $user;
    }
    
    /*...*/
}
```

result request with bad user

```json
{
    "error": {
        "status": 404,
        "code": 5286,
        "message": "user not found"
    }
}
```

---

[Prev : Installation](https://github.com/M6Web/ApiExceptionBundle/blob/master/doc/installation.md)

[Next : Log](https://github.com/M6Web/ApiExceptionBundle/blob/master/doc/log.md)
