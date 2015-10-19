<?php

namespace M6Web\Bundle\ApiExceptionBundle\Tests\Units\Exception;

use atoum\test;
use M6Web\Bundle\ApiExceptionBundle\Exception\Exception as testedClass;

class Exception extends test
{
    public function testGetMessageWithVariablesNotFound()
    {
        $this
            ->given(
                $exception = new TestedClass(500, 'exception {exceptionName} to test')
            )
            ->then
                ->exception(function() use ($exception){
                    $exception->getMessageWithVariables();
                })
                    ->isInstanceOf('\Exception')
                        ->hasCode(500)
                        ->hasMessage('Variable "exceptionName" for exception "M6Web\Bundle\ApiExceptionBundle\Exception\Exception" not found')
        ;
    }

    public function testGetMessageWithVariablesNotString()
    {
        $this
            ->given(
                $exception = new TestedClass(500, 'exception {code} to test')
            )
            ->then
            ->exception(function() use ($exception){
                $exception->getMessageWithVariables();
            })
                ->isInstanceOf('\Exception')
                    ->hasCode(500)
                    ->hasMessage('Variable "code" for exception "M6Web\Bundle\ApiExceptionBundle\Exception\Exception" must be a string, integer found')
        ;
    }
}