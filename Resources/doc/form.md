### Form errors

Create a form

```php
<?php

namespace Acme\DemoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserType
 */
class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('username', 'string', [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length([
                        'min' => 10,
                    ]),
                ],
            ])
            ->add('email', 'string', [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email(),
                ],
            ])
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Acme\DemoBundle\Entity\User'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'user_type';
    }
}
```

Create a new class exception extends `M6Web\Bundle\ApiExceptionBundle\Exception\ValidationFormException`

```php
<?php 

namespace Acme\DemoBundle\Exception;

use M6Web\Bundle\ApiExceptionBundle\Exception\ValidationFormException;

/**
 * Class UserTypeValidationException
 */
class UserTypeValidationException extends ValidationFormException
{
}
```

Configure this new exception

```yaml
# ./app/config/config.yml

m6web_api_exception:
    exception:
        exceptions:
            Acme\DemoBundle\Exception\UserTypeValidationException:
                status: 400
                code: 4723
                message: "validation user form failed"
```

Result with invalid post parameters

```json
{
    "error": {
        "status": 400,
        "code": 4723,
        "message": "validation user form failed",
        "errors": {
            "username": [
                "This value should not be blank.",
                "This value must minimum contain 10 characters"
            ],
            "email": [
                "This value should not be blank.",
                "Email invalid"
            ],
    },
    "stack_trace": [
        ...
    ]
  }
}
```

---

[Prev : Errors](https://github.com/M6Web/ApiExceptionBundle/blob/master/doc/errors.md)

[Next : Match](https://github.com/M6Web/ApiExceptionBundle/blob/master/doc/match.md)
