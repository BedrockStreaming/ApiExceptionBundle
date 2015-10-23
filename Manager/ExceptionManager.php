<?php

namespace M6Web\Bundle\ApiExceptionBundle\Manager;

use M6Web\Bundle\ApiExceptionBundle\Exception\Interfaces\ExceptionInterface;
use M6Web\Bundle\ApiExceptionBundle\Exception\Interfaces\HttpExceptionInterface;

/**
 * Manage exceptions to define public data returned
 */
class ExceptionManager
{
    /**
     * @var array
     */
    protected $defaultConfig;

    /**
     * @var array
     */
    protected $exceptions;

    /**
     * Constructor.
     *
     * @param array $defaultConfig
     * @param array $exceptions
     */
    public function __construct(array $defaultConfig, array $exceptions)
    {
        $this->defaultConfig    = $defaultConfig;
        $this->exceptions       = $exceptions;
    }

    /**
     * Configure Exception
     *
     * @param ExceptionInterface $exception
     *
     * @return ExceptionInterface
     */
    public function configure(ExceptionInterface $exception)
    {
        $exceptionName = get_class($exception);

        $configException = $this->getConfigException($exceptionName);

        $exception->setCode($configException['code']);
        $exception->setMessage($configException['message']);

        if ($exception instanceof HttpExceptionInterface) {
            $exception->setStatusCode($configException['status']);
            $exception->setHeaders($configException['headers']);
        }

        return $exception;
    }

    /**
     * Get config to exception
     *
     * @param string $exceptionName
     *
     * @return array
     */
    protected function getConfigException($exceptionName)
    {
        $exceptionParentName = get_parent_class($exceptionName);

        if (in_array(
            'M6Web\Bundle\ApiExceptionBundle\Exception\Interfaces\ExceptionInterface',
            class_implements($exceptionParentName)
        )) {
            $parentConfig = $this->getConfigException($exceptionParentName);
        } else {
            $parentConfig = $this->defaultConfig;
        }

        if (isset($this->exceptions[$exceptionName])) {
            return array_merge($parentConfig, $this->exceptions[$exceptionName]);
        }

        return $parentConfig;
    }
}
