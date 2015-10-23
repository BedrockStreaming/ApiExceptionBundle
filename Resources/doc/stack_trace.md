# Stack Trace

Additional stack trace exception to json reponse

```yaml
# ./app/config/config.yml

m6web_api_exception:
    exception:
        stack_trace: true
```

result request with bad user and stack trace enabled

```json
{
    "error": {
        "status": 404,
        "code": 5286,
        "message": "user not found",
        "stack_trace": [
            {
                "file": "/src/acme-project/src/Acme/DemoBundle/Controller/DemoController.php",
                "line": 24,
                "function": "getUser",
                "class": "Acme\DemoBundle\Manager\UserManager",
                "type": "->",
                "args": [
                    4
                ]
            },
            {
                "function": "indexAction",
                "class": "Acme\DemoBundle\Controller\DemoController",
                "type": "->",
                "args": [
                    {
                        "attributes": {},
                        "request": {},
                        "query": {},
                        "server": {},
                        "files": {},
                        "cookies": {},
                        "headers": {}
                    }
                ]
            },
            ...
            {
                "file": "/src/acme-project/web/app_dev.php",
                "line": 12,
                "function": "handle",
                "class": "Symfony\Component\HttpKernel\Kernel",
                "type": "->",
                "args": [
                    {
                        "attributes": {},
                        "request": {},
                        "query": {},
                        "server": {},
                        "files": {},
                        "cookies": {},
                        "headers": {}
                    }
                ]
            }
        ]
    }
}
```

---

[Prev : Variables](https://github.com/M6Web/ApiExceptionBundle/blob/master/doc/variables.md)

[Next : Errors](https://github.com/M6Web/ApiExceptionBundle/blob/master/doc/errors.md)
