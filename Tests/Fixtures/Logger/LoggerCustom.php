<?php

namespace M6Web\Bundle\ApiExceptionBundle\Tests\Fixtures\Logger;

use M6Web\Bundle\ApiExceptionBundle\Logger\LoggerInterface;
use Symfony\Bridge\Monolog\Logger as SymfonyLogger;

/**
 * Class LoggerCustom
 */
class LoggerCustom implements LoggerInterface
{
    /**
     * @var SymfonyLogger
     */
    protected $logger;

    /**
     * @var string
     */
    protected $prefix;

    /**
     * Constructor
     *
     * @param SymfonyLogger $logger
     * @param string        $prefix
     */
    public function __construct(SymfonyLogger $logger, $prefix)
    {
        $this->logger = $logger;
        $this->prefix = $prefix;
    }

    /**
     * Create a new log without prefix
     *
     * @param string $message
     * @param string $level
     *
     * @return bool
     */
    public function create($message, $level = 'error')
    {
        return $this->logger->$level($message);
    }
}