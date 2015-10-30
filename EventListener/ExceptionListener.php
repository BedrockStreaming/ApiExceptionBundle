<?php

namespace M6Web\Bundle\ApiExceptionBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface as SymfonyHttpExceptionInterface;
use M6Web\Bundle\ApiExceptionBundle\Manager\ExceptionManager;
use M6Web\Bundle\ApiExceptionBundle\Exception\Interfaces\ExceptionInterface;
use M6Web\Bundle\ApiExceptionBundle\Exception\Interfaces\HttpExceptionInterface;
use M6Web\Bundle\ApiExceptionBundle\Exception\Interfaces\FlattenErrorExceptionInterface;

/**
 * Class ExceptionListener
 */
class ExceptionListener
{
    /**
     * @var boolean
     */
    protected $stackTrace;

    /**
     * @var array
     */
    protected $default;

    /**
     * @var Kernel
     */
    protected $kernel;

    /**
     * @var ExceptionManager
     */
    protected $exceptionManager;

    /**
     * @var boolean
     */
    protected $matchAll;

    /**
     * Constructor
     *
     * @param Kernel           $kernel
     * @param ExceptionManager $exceptionManager
     * @param boolean          $matchAll
     * @param array            $default
     * @param boolean          $stackTrace
     */
    public function __construct(
        Kernel $kernel,
        ExceptionManager $exceptionManager,
        $matchAll,
        array $default,
        $stackTrace = false
    ) {
        $this->kernel           = $kernel;
        $this->exceptionManager = $exceptionManager;
        $this->matchAll         = $matchAll;
        $this->default          = $default;
        $this->stackTrace       = $stackTrace;
    }

    /**
     * Format response exception
     *
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if (!($event->getRequest())
            || ($this->matchAll === false && !$this->isApiException($exception))
        ) {
            return;
        }

        if ($this->isApiException($exception)) {
            $exception = $this->exceptionManager->configure($exception);
        }

        $statusCode = $this->getStatusCode($exception);

        $data['error']['status']  = $statusCode;

        if ($code = $exception->getCode()) {
            $data['error']['code'] = $code;
        }

        $data['error']['message'] = $this->getMessage($exception);

        if ($this->isFlattenErrorException($exception)) {
            $data['error']['errors'] = $exception->getFlattenErrors();
        }

        if ($this->stackTrace) {
            $data['error']['stack_trace'] = $exception->getTrace();
        }

        $response = new JsonResponse($data, $statusCode, $this->getHeaders($exception));
        $event->setResponse($response);
    }

    /**
     * Get exception status code
     *
     * @param \Exception $exception
     *
     * @return integer
     */
    private function getStatusCode(\Exception $exception)
    {
        $statusCode = $this->default['status'];

        if ($exception instanceof SymfonyHttpExceptionInterface
            || $exception instanceof HttpExceptionInterface
        ) {
            $statusCode = $exception->getStatusCode();
        }

        return $statusCode;
    }

    /**
     * Get exception message
     *
     * @param \Exception $exception
     *
     * @return integer
     */
    private function getMessage(\Exception $exception)
    {
        $message = $exception->getMessage();

        if ($this->isApiException($exception)) {
            $message = $exception->getMessageWithVariables();
        }

        return $message;
    }

    /**
     * Get exception headers
     *
     * @param \Exception $exception
     *
     * @return array
     */
    private function getHeaders(\Exception $exception)
    {
        $headers = $this->default['headers'];

        if ($exception instanceof SymfonyHttpExceptionInterface
            || $exception instanceof HttpExceptionInterface
        ) {
            $headers = $exception->getHeaders();
        }

        return $headers;
    }

    /**
     * Is api exception
     *
     * @param \Exception $exception
     *
     * @return boolean
     */
    private function isApiException(\Exception $exception)
    {
        if ($exception instanceof ExceptionInterface) {
            return true;
        }

        return false;
    }

    /**
     * Is flatten error exception
     *
     * @param \Exception $exception
     *
     * @return boolean
     */
    private function isFlattenErrorException(\Exception $exception)
    {
        if ($exception instanceof FlattenErrorExceptionInterface) {
            return true;
        }

        return false;
    }
}
