<?php

namespace M6Web\Bundle\ApiExceptionBundle\Tests\Fixtures\Exception;

use M6Web\Bundle\ApiExceptionBundle\Exception\BadRequestHttpException;

/**
 * Class TypeNotFoundHttpException
 */
class TypeNotFoundHttpException extends BadRequestHttpException
{
    /**
     * @var string
     */
    protected $Type_1;

    /**
     * @param string $Type_1
     */
    public function __construct($Type_1 = 'view')
    {
        $this->Type_1 = $Type_1;
    }
}