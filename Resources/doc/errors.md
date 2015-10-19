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
    protected $errors;
    
    /**
     * @param mixed $errors
     */
    public function __construct($errors)
    {
        $this->errors = $errors;
    }
    
    /**
     * Get errors
     *
     * @return array
     */
    public function getFlattenErrors()
    {
        /* your algo */
    }
}
```
*Example: `M6Web\Bundle\ApiExceptionBundle\Exception\ValidationFormException`*

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

[Prev : Stack Trace](https://github.com/M6Web/ApiExceptionBundle/blob/master/doc/stack_trace.md)

[Next : Form](https://github.com/M6Web/ApiExceptionBundle/blob/master/doc/form.md)
