<?php

namespace M6Web\Bundle\ApiExceptionBundle\Exception\Interfaces;

/**
 * Interface ExceptionInterface
 */
interface ExceptionInterface
{
    /**
     * Set code
     *
     * @param integer $code
     */
    public function setCode($code);

    /**
     * Get code
     */
    public function getCode();

    /**
     * Set Message
     *
     * @param string $message
     */
    public function setMessage($message);

    /**
     * Get message
     */
    public function getMessage();

    /**
     * Set log level
     *
     * @param string $level
     */
    public function setLevel($level);

    /**
     * Get log level
     */
    public function getLevel();

    /**
     * Get message with variables
     */
    public function getMessageWithVariables();
}
