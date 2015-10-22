<?php

namespace M6Web\Bundle\ApiExceptionBundle\Exception;

use M6Web\Bundle\ApiExceptionBundle\Exception\Interfaces\ExceptionInterface;

/**
 * class Exception
 */
class Exception extends \Exception implements ExceptionInterface
{
    const VARIABLE_REGEX = "/(\{[a-zA-Z0-9\_]+\})/";

    /**
     * Constructor
     *
     * @param integer $code
     * @param string  $message
     */
    public function __construct(
        $code = 0,
        $message = ''
    ) {
        parent::__construct($message, $code);
    }

    /**
     * Set code
     *
     * @param integer $code
     *
     * @return self
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return self
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message with variables
     *
     * @throws \Exception
     *
     * @return string
     */
    public function getMessageWithVariables()
    {
        $message = $this->message;

        preg_match(self::VARIABLE_REGEX, $message, $variables);

        foreach ($variables as $variable) {

            $variableName = substr($variable, 1, -1);

            if (!isset($this->$variableName)) {
                throw new \Exception(sprintf(
                    'Variable "%s" for exception "%s" not found',
                    $variableName,
                    get_class($this)
                ), 500);
            }

            if (!is_string($this->$variableName)) {
                throw new \Exception(sprintf(
                    'Variable "%s" for exception "%s" must be a string, %s found',
                    $variableName,
                    get_class($this),
                    gettype($this->$variableName)
                ), 500);
            }

            $message = str_replace($variable, $this->$variableName, $message);
        }

        return $message;
    }
}
