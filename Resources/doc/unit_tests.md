# Unit Tests

Exception configuration is initialized in listener `kernel.exception`. So, unit tests do not configure exceptions. It is only possible to test the exception thrown.

Example :

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

Unit test with Atoum for method `getUser`

```php
<?php 

namespace Acme\DemoBundle\Tests\Units\Manager;

use Acme\DemoBundle\Entity\User;
use Acme\DemoBundle\Exception\UserNotFoundException;
use Acme\DemoBundle\Manager\UserManager as TestedClass;

/**
 * Class UserManager
 */
class UserManager
{
    /**
     * @param integer $id
     *
     * @throws UserNotFoundException
     *
     * @return User $user
     */
    public function testGetUser()
    {
        $this
            ->given(
                $idUserUnknown = 0;
                $userManager = new TestedClass(...)
            )
            ->then
                ->exception(function() use ($userManager, $idUserUnknown) {
                    $userManager->getUser($idUserUnknown);
                })
                    ->isInstanceOf('Acme\DemoBundle\Exception\UserNotFoundException')
                    ->hasCode(?) // No sens, because configuration not initialized
                    ->hasMessage(?) // No sens, because configuration not initialized
        ;
    }
}
```

---

[Prev : Configuration](https://github.com/M6Web/ApiExceptionBundle/blob/master/Resources/doc/configuration.md)

[Next : About](https://github.com/M6Web/ApiExceptionBundle/blob/master/Resources/doc/about.md)
