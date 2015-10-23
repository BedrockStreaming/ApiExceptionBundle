# Variables

Add variables in message for your exception

```php
<?php 

namespace Acme\DemoBundle\Exception;

use M6Web\Bundle\ApiExceptionBundle\Exception\HttpException;

/**
 * Class UserNotFoundException
 */
class UserNotFoundException extends HttpException
{
    /**
     * @var integer
     */
    protected $id;
    
    /**
     * @param integer $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }
}
```

Initialize variables when you use your exception

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

    if (!$user) {
        throw new UserNotFoundException($id);
    }
    
    /*...*/
}
```

Add variables in configuration message.

```yaml
# ./app/config/config.yml

m6web_api_exception:
    exception:
        exceptions:
            Acme\DemoBundle\Exception\UserNotFoundException:
                status: 404
                code: 5286
                message: "user {id} not found"
```
*Variables must be between `{}` (example `{myVariable}`)*

Result with user `1856` not found

```json
{
    "error": {
        "status": 404,
        "code": 5286,
        "message": "user 1856 not found"
    }
}
```

---

[Prev : Usage](https://github.com/M6Web/ApiExceptionBundle/blob/master/doc/usage.md)

[Next : Variables](https://github.com/M6Web/ApiExceptionBundle/blob/master/doc/variables.md)
