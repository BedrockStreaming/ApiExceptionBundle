<?php

namespace M6Web\Bundle\ApiExceptionBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface as SymfonyHttpExceptionInterface;
use M6Web\Bundle\ApiExceptionBundle\Manager\ExceptionManager;
use M6Web\Bundle\ApiExceptionBundle\Logger\LoggerInterface;
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
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var boolean
     */
    protected $matchAll;

    /**
     * Constructor
     *
     * @param Kernel           $kernel
     * @param ExceptionManager $exceptionManager
     * @param LoggerInterface  $logger
     * @param boolean          $matchAll
     * @param array            $default
     * @param boolean          $stackTrace
     */
    public function __construct(
        Kernel $kernel,
        ExceptionManager $exceptionManager,
        LoggerInterface $logger,
        $matchAll,
        array $default,
        $stackTrace = false
    ) {
        $this->kernel           = $kernel;
        $this->exceptionManager = $exceptionManager;
        $this->logger           = $logger;
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

        if (!($request = $event->getRequest())
            || ($this->matchAll === false && !$this->isApiException($exception))
        ) {
            return;
        }

        if ($this->isApiException($exception)) {
            $exception = $this->exceptionManager->configure($exception);
        }

        $statusCode = $this->getStatusCode($exception);

        $data['error']['status']  = $statusCode;
        $data['error']['code']    = $exception->getCode();
        $data['error']['message'] = $this->getMessage($exception);

        if ($this->isFlattenErrorException($exception)) {
            $data['error']['errors'] = $exception->getFlattenErrors();
        }

        if ($this->stackTrace) {
            $data['error']['stack_trace'] = $exception->getTrace();
        }

        $this->writeLog($exception);

        $response = new JsonResponse($data, $statusCode, $this->getHeaders($exception));
        $event->setResponse($response);
    }

    /**
     * Write log exception
     *
     * @param mixed $exception
     */
    public function writeLog($exception)
    {
        $statusCode = $this->getStatusCode($exception);

        $log = sprintf(
            'Code %d - Status %d | Message : %s',
            $exception->getCode(),
            $statusCode,
            $exception->getMessage()
        );

        if ($this->isFlattenErrorException($exception)) {
            $errors = $exception->getFlattenErrors();

            $logErrors = '';
            foreach ($errors as $field => $error) {
                $logErrors .= sprintf('%s :', $field);
                foreach ($error as $message) {
                    $logErrors .= sprintf(' %s', $message);
                }
                $logErrors .= ' - ';
            }
            $logErrors = substr($logErrors, 0, -3);

            $log .= sprintf(' | Errors : [ %s ]', $logErrors);
        }

        if ($this->stackTrace) {
            $log .= sprintf(' | Stack Trace : [ %s ]', json_encode($exception->getTrace()));
        }

        $level = $this->getLevel($exception);

        $this->logger->create($log, $level);
    }

    /**
     * Get exception status code
     *
     * @param $exception
     *
     * @return integer
     */
    private function getStatusCode($exception)
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
     * @param $exception
     *
     * @return integer
     */
    private function getMessage($exception)
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
     * @param $exception
     *
     * @return array
     */
    private function getHeaders($exception)
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
     * Get exception level
     *
     * @param $exception
     *
     * @return string
     */
    private function getLevel($exception)
    {
        $level = $this->default['level'];

        if ($this->isApiException($exception)) {
            $level = $exception->getLevel();
        }

        return $level;
    }

    /**
     * Is api exception
     *
     * @param $exception
     *
     * @return boolean
     */
    private function isApiException($exception)
    {
        if ($exception instanceof ExceptionInterface) {
            return true;
        }

        return false;
    }

    /**
     * Is flatten error exception
     *
     * @param $exception
     *
     * @return boolean
     */
    private function isFlattenErrorException($exception)
    {
        if ($exception instanceof FlattenErrorExceptionInterface) {
            return true;
        }

        return false;
    }
}
