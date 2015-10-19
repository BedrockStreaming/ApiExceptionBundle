<?php

namespace M6Web\Bundle\ApiExceptionBundle\Exception;

use M6Web\Bundle\ApiExceptionBundle\Exception\Interfaces\HttpExceptionInterface;

/**
 * class HttpException
 */
class HttpException extends Exception implements HttpExceptionInterface
{
    /**
     * @var integer
     */
    protected $statusCode;

    /**
     * @var array
     */
    protected $headers;

    /**
     * Constructor
     *
     * @param integer $statusCode
     * @param integer $code
     * @param string  $message
     * @param string  $level
     * @param array   $headers
     */
    public function __construct(
        $statusCode = 500,
        $code = 0,
        $message = '',
        $level = 'error',
        array $headers = []
    ) {
        $this->statusCode = $statusCode;
        $this->headers    = $headers;
        parent::__construct($code, $message, $level);
    }

    /**
     * Set status code
     *
     * @param integer $statusCode
     *
     * @return self
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Get status code
     *
     * @return string
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Set headers
     *
     * @param array $headers
     *
     * @return self
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Get headers
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }
}
