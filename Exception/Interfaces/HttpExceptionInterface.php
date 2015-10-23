<?php

namespace M6Web\Bundle\ApiExceptionBundle\Exception\Interfaces;

/**
 * Interface HttpExceptionInterface
 */
interface HttpExceptionInterface
{
    /**
     * Set status code
     *
     * @param integer $statusCode
     */
    public function setStatusCode($statusCode);

    /**
     * Get status code
     */
    public function getStatusCode();

    /**
     * Set headers
     *
     * @param array $headers
     */
    public function setHeaders(array $headers);

    /**
     * Get headers
     */
    public function getHeaders();
}
