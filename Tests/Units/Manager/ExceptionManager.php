<?php

namespace M6Web\Bundle\ApiExceptionBundle\Tests\Units\Manager;

use atoum\test;
use M6Web\Bundle\ApiExceptionBundle\Manager\ExceptionManager as TestedClass;
use M6Web\Bundle\ApiExceptionBundle\Exception\BadRequestException;
use M6Web\Bundle\ApiExceptionBundle\Exception\ValidationFormException;
use M6Web\Bundle\ApiExceptionBundle\Tests\Fixtures\Exception\TypeNotFoundHttpException;

class ExceptionManager extends test
{
    /**
     * @return \mock\Symfony\Component\Form\Form
     */
    protected function getFormMock()
    {
        $this->mockGenerator->orphanize('__construct');
        $form = new \mock\Symfony\Component\Form\Form;

        return $form;
    }

    /**
     * @return array
     */
    protected function getDefaultConfig()
    {
        return [
            'status'    => 400,
            'code'      => 1000,
            'message'   => 'bad request',
            'level'     => 'error',
            'headers'   => []
        ];
    }

    /**
     * @return array
     */
    protected function getExceptionsConfig()
    {
        return [
            'M6Web\\Bundle\\ApiExceptionBundle\\Exception\\ValidationFormException' => [
                'code'      => 1001,
                'message'   => 'form validation failed',
                'headers'   => [
                    'Exception' => 'form validation failed'
                ]
            ],
            'M6Web\\Bundle\\ApiExceptionBundle\\Tests\\Fixtures\\Exception\\TypeNotFoundHttpException' => [
                'status'  => 404,
                'code'    => 1003,
                'message' => 'notify type not found',
                'level'   => 'warning'
            ],
            'M6Web\\Bundle\\ApiExceptionBundle\\Exception\\BadRequestException' => [
            ]
        ];
    }

    public function testConfigure()
    {
        $this
            ->given(
                $defaultConfig = $this->getDefaultConfig(),
                $exceptionsConfig = $this->getExceptionsConfig(),
                $badRequestException = new BadRequestException(),
                $validationFormException = new ValidationFormException($this->getFormMock()),
                $typeNotFoundException = new TypeNotFoundHttpException(),
                $exceptionManager = new TestedClass($defaultConfig, $exceptionsConfig)
            )
            ->then

            ->object($exception = $exceptionManager->configure($badRequestException))
                ->isInstanceOf('M6Web\Bundle\ApiExceptionBundle\Exception\BadRequestException')
                ->integer($exception->getCode())
                    ->isEqualTo($defaultConfig['code'])
                ->string($exception->getMessage())
                    ->isEqualTo($defaultConfig['message'])
                ->string($exception->getLevel())
                    ->isEqualTo($defaultConfig['level'])

            ->object($exception = $exceptionManager->configure($validationFormException))
                ->isInstanceOf('M6Web\Bundle\ApiExceptionBundle\Exception\ValidationFormException')
                ->integer($exception->getCode())
                    ->isEqualTo($exceptionsConfig['M6Web\Bundle\ApiExceptionBundle\Exception\ValidationFormException']['code'])
                ->string($exception->getMessage())
                    ->isEqualTo($exceptionsConfig['M6Web\Bundle\ApiExceptionBundle\Exception\ValidationFormException']['message'])
                ->string($exception->getLevel())
                    ->isEqualTo($defaultConfig['level'])
                ->integer($exception->getStatusCode())
                    ->isEqualTo($defaultConfig['status'])
                ->array($headers = $exception->getHeaders())
                    ->hasSize(1)
                    ->string($headers['Exception'])
                        ->isEqualTo($exceptionsConfig['M6Web\Bundle\ApiExceptionBundle\Exception\ValidationFormException']['headers']['Exception'])

            ->object($exception = $exceptionManager->configure($typeNotFoundException))
                ->isInstanceOf('M6Web\Bundle\ApiExceptionBundle\Tests\Fixtures\Exception\TypeNotFoundHttpException')
                ->integer($exception->getCode())
                    ->isEqualTo($exceptionsConfig['M6Web\Bundle\ApiExceptionBundle\Tests\Fixtures\Exception\TypeNotFoundHttpException']['code'])
                ->string($exception->getMessage())
                    ->isEqualTo($exceptionsConfig['M6Web\Bundle\ApiExceptionBundle\Tests\Fixtures\Exception\TypeNotFoundHttpException']['message'])
                ->string($exception->getLevel())
                    ->isEqualTo($exceptionsConfig['M6Web\Bundle\ApiExceptionBundle\Tests\Fixtures\Exception\TypeNotFoundHttpException']['level'])
                ->integer($exception->getStatusCode())
                    ->isEqualTo($exceptionsConfig['M6Web\Bundle\ApiExceptionBundle\Tests\Fixtures\Exception\TypeNotFoundHttpException']['status'])
                ->array($exception->getHeaders())
                    ->hasSize(0)
                    ->isEqualTo($defaultConfig['headers'])
        ;
    }
}