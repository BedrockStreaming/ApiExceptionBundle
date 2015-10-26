# Errors

Json Responses displays an additional `errors` array if your exception implement `M6Web\Bundle\ApiExceptionBundle\Exception\Interfaces\FlattenErrorExceptionInterface`

Just create an exception with `getFlattenErrors` method to transform errors to array

```php
<?php 

namespace Acme\DemoBundle\Exception;

use M6Web\Bundle\ApiExceptionBundle\Exception\Interfaces\FlattenErrorExceptionInterface;

/**
 * Class UnknowException
 */
class UnknowException implements FlattenErrorExceptionInterface
{
    /**
     * @var mixed
     */
    protected $var1;
    
    /**
     * @var mixed
     */
    protected $var2;
    
    /**
     * @param mixed $errors
     */
    public function __construct($var1, $var2)
    {
        $this->var1 = $var1;
        $this->var2 = $var2;
    }
    
    /**
     * Get errors
     *
     * @return array
     */
    public function getFlattenErrors()
    {
        $errors = [];
        
        /* your algo with $var1 and $var2 to compose array */
        
        return $errors
    }
}
```
*Example: `M6Web\Bundle\ApiExceptionBundle\Exception\ValidationFormException`*

Use your exception with errors

```php
<?php 

namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Acme\DemoBundle\Exception\UnknowException;

/**
 * Class DefaultController
 */
class DefaultController extends Controller
{
    /*...*/

    throw new UnknowException($var1, $var2);
            
    /*...*/
}
```

result to json reponse

```json
{
    "error": {
        "status": 400,
        "code": 1526,
        "message": "error during process",
        "errors": {
            "error1": [
                "This is first problem with error1",
                "This is second problem with error1"
            ],
            "error2": [
                "This is problem with error2"
            ],
    },
    "stack_trace": [
        ...
    ]
  }
}
```

---

[Prev : Stack Trace](https://github.com/M6Web/ApiExceptionBundle/blob/master/Resources/doc/stack_trace.md)

[Next : Form](https://github.com/M6Web/ApiExceptionBundle/blob/master/Resources/doc/form.md)
