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
     * Get message with variables
     */
    public function getMessageWithVariables();
}
