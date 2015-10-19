# Configuration

### Description

Node to configure bundle `m6web_api_exception`

- `logger` *(___array___)* :
    - `service` *(___string___, default `m6web_api_exception.logger`)* : Service logger uses to log exceptions
    - `prefix` *(___string___, default `null`)*: Add a prefix to log exceptions
- `exception` *(___array___)* :
    - `match_all` *(___boolean___, default `true`)* : Match all exception, M6WebApiException and others
    - `stack_trace` *(___boolean___, default `false`)* : Add stack trace exception to JsonResponse and log
    - `default` *(___array___)* : Default configuration to exceptions
        - `status` *(___integer___, default `500`)* : Http status
        - `code` *(___integer___, default `0`)* : Code error specific to project
        - `message` *(___string___, default `internal server error`)* : Error message
        - `level` *(___enum___, allowed `notice` `warning` `error`, default `error`)* : Log level
        - `headers` *(___array___, default `[]`)* : Headers to JsonResponse
    - `exceptions` *(___array___)* : Extends default configuration by exceptions
        - `Acme\DemoBundle\Exception\BadRequestException` *(___namespace exception___)* 
            - `status` *(___integer___)* : Http status
            - `code` *(___integer___)* : Code error specific to project
            - `message` *(___string___)* : Error message
            - `level` *(___enum___, possibles `notice` `warning` `error`)* : Log level
            - `headers` *(___array___)* : Headers to JsonResponse
        - `Acme\DemoBundle\Exception\MyOtherException`
            - `...`
        - `...`
    
*NB : `status` and `headers` are enabled only for exceptions that implements `M6Web\Bundle\ApiExceptionBundle\Exception\Interfaces\HttpExceptionInterface`*
    
### Configuration example

```yaml
# ./app/config/config.yml

m6web_api_exception:
    logger:
        prefix: "Prefix exception ===>"
    exception:
        stack_trace: true
        default:
            status: 400
            code: 1000
        exceptions:
            Acme\DemoBundle\Exception\ValidationFormException:
                code: 1001
                message: "form validation failed"
                headers:
                    Exception: "form validation failed"
            Acme\DemoBundle\Exception\TypeNotFoundException:
                status: 404
                code: 1002
                message: "type not found"
                level: "warning"
```

---

[Prev : Log](https://github.com/M6Web/ApiExceptionBundle/blob/master/doc/log.md)

[Next : Unit Tests](https://github.com/M6Web/ApiExceptionBundle/blob/master/doc/unit_tests.md)
