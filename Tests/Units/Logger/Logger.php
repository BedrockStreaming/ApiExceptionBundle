<?php

namespace M6Web\Bundle\ApiExceptionBundle\Tests\Units\Logger;

use atoum\test;
use M6Web\Bundle\ApiExceptionBundle\Logger\Logger as testedClass;

class Logger extends test
{
    const LOG_PREFIX = 'My Super Prefix ==> ';

    /**
     * @return array
     */
    protected function levelsDataProvider()
    {
        return [['notice'], ['warning'], ['error']];
    }

    /**
     * @return \mock\Symfony\Bridge\Monolog\Logger
     */
    protected function getSymfonyLoggerMock()
    {
        $this->mockGenerator->orphanize('__construct');
        $symfonyLogger = new \mock\Symfony\Bridge\Monolog\Logger;

        $symfonyLogger->getMockController()->notice  = function($message) { return $message; };
        $symfonyLogger->getMockController()->warning = function($message) { return $message; };
        $symfonyLogger->getMockController()->error   = function($message) { return $message; };

        return $symfonyLogger;
    }

    /**
     * @param $level
     *
     * @dataProvider levelsDataProvider
     */
    public function testCreateLogWithLevel($level)
    {
        $message = 'Chuck Norris is a god !';
        $outMessage = self::LOG_PREFIX.$message;

        $this
            ->given(
                $symfonyLogger = $this->getSymfonyLoggerMock(),
                $logger = new testedClass($symfonyLogger, self::LOG_PREFIX),
                $logger->create($message, $level)
            )
            ->then
                ->mock($symfonyLogger)
                    ->call($level)
                        ->withArguments($outMessage)
                        ->once()
        ;
    }

    public function testCreateLogWithoutLevel()
    {
        $message = 'Chuck Norris is a god !';
        $outMessage = self::LOG_PREFIX.$message;

        $this
            ->given(
                $symfonyLogger = $this->getSymfonyLoggerMock(),
                $logger = new testedClass($symfonyLogger, self::LOG_PREFIX),
                $logger->create($message)
            )
            ->then
            ->mock($symfonyLogger)
                ->call('error')
                    ->withArguments($outMessage)
                    ->once()
        ;
    }
}