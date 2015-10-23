<?php

namespace M6Web\Bundle\ApiExceptionBundle\Exception;

use Symfony\Component\Form\FormInterface;
use M6Web\Bundle\ApiExceptionBundle\Exception\Interfaces\FlattenErrorExceptionInterface;

/**
 * Class ValidationFormException
 */
class ValidationFormException extends HttpException implements FlattenErrorExceptionInterface
{
    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * Constructor
     *
     * @param FormInterface $form
     * @param integer       $statusCode
     * @param integer       $code
     * @param string        $message
     * @param array         $headers
     */
    public function __construct(
        FormInterface $form,
        $statusCode = 500,
        $code = 0,
        $message = '',
        array $headers = []
    ) {
        $this->form = $form;
        parent::__construct($statusCode, $code, $message, $headers);
    }

    /**
     * Get form
     *
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Flatten form errors
     *
     * @param FormInterface $form
     * @param boolean       $subForm
     *
     * @return array
     */
    public function getFlattenErrors(FormInterface $form = null, $subForm = false)
    {
        $form    = $form ?: $this->form;
        $flatten = [];

        foreach ($form->getErrors() as $error) {
            if ($subForm) {
                $flatten[] = $error->getMessage();
            } else {
                $path = $error->getCause()->getPropertyPath();

                if (!array_key_exists($path, $flatten)) {
                    $flatten[$path] = [$error->getMessage()];
                    continue;
                }

                $flatten[$path][] = $error->getMessage();
            }
        }

        $subForm = true;
        foreach ($form->all() as $key => $child) {
            $childErrors = $this->getFlattenErrors($child, $subForm);

            if (!empty($childErrors)) {
                $flatten[$key] = $childErrors;
            }
        }

        return $flatten;
    }
}
