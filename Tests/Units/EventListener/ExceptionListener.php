<?php

namespace M6Web\Bundle\ApiExceptionBundle\Tests\Units\EventListener;

use atoum\test;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use M6Web\Bundle\ApiExceptionBundle\Manager\ExceptionManager;
use M6Web\Bundle\ApiExceptionBundle\Exception\BadRequestException;
use M6Web\Bundle\ApiExceptionBundle\Exception\BadRequestHttpException;
use M6Web\Bundle\ApiExceptionBundle\Tests\Fixtures\Exception\TypeNotFoundHttpException;
use M6Web\Bundle\ApiExceptionBundle\EventListener\ExceptionListener as TestedClass;

class ExceptionListener extends test
{
    /**
     * @param       $path
     * @param       $method
     * @param array $get
     * @param array $post
     * @param array $headers
     *
     * @return \mock\Symfony\Component\HttpFoundation\Request
     */
    protected function getRequestMock(
        $path,
        $method,
        array $get = [],
        array $post = [],
        array $headers = []
    ) {
        $this->mockGenerator->orphanize('__construct');
        $request = new \mock\Symfony\Component\HttpFoundation\Request();

        $request->query      = new ParameterBag($get);
        $request->request    = new ParameterBag($post);
        $request->headers    = new HeaderBag($headers);
        $request->attributes = new ParameterBag(['debug' => true]);

        $request->getMockController()->getMethod = strtoupper($method);
        $request->getMockController()->getSchemeAndHttpHost = 'http://test.tld';
        $request->getMockController()->getBaseUrl = '';
        $request->getMockController()->getPathInfo = $path;

        return $request;
    }

    /**
     * @param bool|true $isSuccessful
     *
     * @return \mock\Symfony\Component\HttpFoundation\Response
     */
    protected function getResponseMock($isSuccessful = true)
    {
        $this->mockGenerator->orphanize('__construct');

        $response = new \mock\Symfony\Component\HttpFoundation\Response();
        $response->getMockController()->isSuccessful = $isSuccessful;

        return $response;
    }

    /**
     * @param $request
     * @param mixed $exception
     *
     * @return \mock\Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent
     */
    protected function getGetResponseForExceptionEventMock($request, $exception)
    {
        $this->mockGenerator->orphanize('__construct');
        $this->mockGenerator->shuntParentClassCalls();

        $event = new \mock\Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent();
        $event->getMockController()->getRequest = $request;
        $event->getMockController()->getException = $exception;
        $event->getMockController()->isMasterRequest = true;

        return $event;

    }

    /**
     * @return \mock\Symfony\Component\HttpKernel\Kernel
     */
    protected function getKernelMock()
    {
        $this->mockGenerator->orphanize('__construct');
        $kernel = new \mock\Symfony\Component\HttpKernel\Kernel;

        $kernel->getMockController()->getEnvironment = 'test';

        return $kernel;
    }

    /**
     * @param array $configException
     *
     * @return \mock\M6Web\Bundle\ApiExceptionBundle\Manager\Exception
     */
    protected function getManagerException(array $configException)
    {
        $managerException = new ExceptionManager(
            $configException,
            []
        );

        return $managerException;
    }

    /**
     * @return \mock\Symfony\Component\Form\Form
     */
    protected function getFormMock()
    {
        $this->mockGenerator->orphanize('__construct');
        $form = new \mock\Symfony\Component\Form\Form;

        return $form;
    }

    /**
     * @param array   $errors
     *
     * @return \mock\M6\Heartbeat\Exception\ValidationFormException
     */
    protected function getFormValidationExceptionMock(array $errors)
    {
        $form = $this->getFormMock();
        $exception = new \mock\M6Web\Bundle\ApiExceptionBundle\Exception\ValidationFormException($form);

        $exception->getMockController()->getFlattenErrors = $errors;

        return $exception;
    }

    public function testOnKernelExceptionWithMatchAllAndConfigExceptionSameDefault()
    {
        $matchAll   = true;
        $statusCode = 400;
        $code       = 1000;
        $message    = 'This is an exception for test listener';
        $headers    = [
            'Exception' => 'bad request'
        ];
        $configExceptionDefault = [
            'status'  => $statusCode,
            'code'    => $code,
            'message' => $message,
            'headers' => $headers,
        ];
        $data = [
            'error' => [
                'status'  => $statusCode,
                'code'    => $code,
                'message' => $message,
            ]
        ];

        $this
            ->given(
                $exception         = new BadRequestException,
                $kernel            = $this->getKernelMock(),
                $exceptionManager  = $this->getManagerException($configExceptionDefault),
                $request           = $this->getRequestMock('/test-uri/', 'GET'),
                $event             = $this->getGetResponseForExceptionEventMock($request, $exception),
                $exceptionListener = new TestedClass($kernel, $exceptionManager, $matchAll, $configExceptionDefault, false),
                $exceptionListener->onKernelException($event),
                $validResponse     = new JsonResponse($data, $statusCode, $headers)
            )
            ->then
            ->mock($event)
                ->call('setResponse')
                    ->withArguments($validResponse)
                    ->once()
        ;
    }

    public function testOnKernelExceptionWithMatchAllAndConfigExceptionSameDefaultWithoutCode()
    {
        $matchAll   = true;
        $statusCode = 400;
        $code       = 0;
        $message    = 'This is an exception for test listener';
        $headers    = [
            'Exception' => 'bad request'
        ];
        $configExceptionDefault = [
            'status'  => $statusCode,
            'code'    => $code,
            'message' => $message,
            'headers' => $headers,
        ];
        $data = [
            'error' => [
                'status'  => $statusCode,
                'message' => $message,
            ]
        ];

        $this
            ->given(
                $exception         = new BadRequestException,
                $kernel            = $this->getKernelMock(),
                $exceptionManager  = $this->getManagerException($configExceptionDefault),
                $request           = $this->getRequestMock('/test-uri/', 'GET'),
                $event             = $this->getGetResponseForExceptionEventMock($request, $exception),
                $exceptionListener = new TestedClass($kernel, $exceptionManager, $matchAll, $configExceptionDefault, false),
                $exceptionListener->onKernelException($event),
                $validResponse     = new JsonResponse($data, $statusCode, $headers)
            )
            ->then
            ->mock($event)
            ->call('setResponse')
                ->withArguments($validResponse)
                ->once()
        ;
    }

    public function testOnKernelExceptionWithMatchAllAndConfigExceptionDifferentDefaultWithoutHttpException()
    {
        $statusCodeDefault = 500;
        $matchAll   = true;
        $statusCode = 400;
        $code       = 1000;
        $message    = 'This is an exception for test listener';
        $headers    = [
            'Exception' => 'bad request'
        ];
        $configExceptionDefault = [
            'status'  => $statusCodeDefault,
            'code'    => 0,
            'message' => $message,
            'headers' => $headers,
        ];
        $configException = $configExceptionDefault;
        $configException['status']  = $statusCode;
        $configException['code']    = $code;
        $configException['headers'] = [];
        $data = [
            'error' => [
                'status'  => $statusCodeDefault,
                'code'    => $code,
                'message' => $message,
            ]
        ];

        $this
            ->given(
                $exception         = new BadRequestException,
                $kernel            = $this->getKernelMock(),
                $exceptionManager  = $this->getManagerException($configException),
                $request           = $this->getRequestMock('/test-uri/', 'GET'),
                $event             = $this->getGetResponseForExceptionEventMock($request, $exception),
                $exceptionListener = new TestedClass($kernel, $exceptionManager, $matchAll, $configExceptionDefault),
                $exceptionListener->onKernelException($event),
                $validResponse     = new JsonResponse($data, $statusCodeDefault, $headers)
            )
            ->then
            ->mock($event)
                ->call('setResponse')
                    ->withArguments($validResponse)
                    ->once()
        ;
    }

    public function testOnKernelExceptionWithMatchAllAndConfigExceptionDifferentDefaultWithHttpException()
    {
        $matchAll   = true;
        $statusCode = 400;
        $code       = 1000;
        $message    = 'This is an exception for test listener';
        $headers    = [
            'Exception' => 'bad request'
        ];
        $configExceptionDefault = [
            'status'  => '500',
            'code'    => 0,
            'message' => $message,
            'headers' => [],
        ];
        $configException = $configExceptionDefault;
        $configException['status']  = $statusCode;
        $configException['code']    = $code;
        $configException['headers'] = $headers;
        $data = [
            'error' => [
                'status'  => $statusCode,
                'code'    => $code,
                'message' => $message,
            ]
        ];

        $this
            ->given(
                $exception         = new BadRequestHttpException,
                $kernel            = $this->getKernelMock(),
                $exceptionManager  = $this->getManagerException($configException),
                $request           = $this->getRequestMock('/test-uri/', 'GET'),
                $event             = $this->getGetResponseForExceptionEventMock($request, $exception),
                $exceptionListener = new TestedClass($kernel, $exceptionManager, $matchAll, $configExceptionDefault),
                $exceptionListener->onKernelException($event),
                $validResponse     = new JsonResponse($data, $statusCode, $headers)
            )
            ->then
            ->mock($event)
                ->call('setResponse')
                    ->withArguments($validResponse)
                    ->once()
        ;
    }

    public function testOnKernelExceptionWithMatchAllWithStackTrace()
    {
        $stackTrace = true;
        $matchAll   = true;
        $statusCode = 400;
        $code       = 1000;
        $message    = 'This is an exception for test listener';
        $headers    = [
            'Exception' => 'bad request'
        ];
        $configExceptionDefault = [
            'status'  => $statusCode,
            'code'    => $code,
            'message' => $message,
            'headers' => $headers,
        ];
        $data = [
            'error' => [
                'status'      => $statusCode,
                'code'        => $code,
                'message'     => $message,
            ]
        ];

        $this
            ->given(
                $exception         = new BadRequestException,
                $data['error']['stack_trace'] = $exception->getTrace(),
                $kernel            = $this->getKernelMock(),
                $exceptionManager  = $this->getManagerException($configExceptionDefault),
                $request           = $this->getRequestMock('/test-uri/', 'GET'),
                $event             = $this->getGetResponseForExceptionEventMock($request, $exception),
                $exceptionListener = new TestedClass($kernel, $exceptionManager, $matchAll, $configExceptionDefault, $stackTrace),
                $exceptionListener->onKernelException($event),
                $validResponse     = new JsonResponse($data, $statusCode, $headers)
            )
            ->then
            ->mock($event)
                ->call('setResponse')
                    ->withArguments($validResponse)
                    ->once()
        ;
    }

    public function testOnKernelExceptionWithMatchAllWithFlattenError()
    {
        $matchAll   = true;
        $statusCode = 400;
        $code       = 1000;
        $message    = 'This is an exception for test listener';
        $headers    = [
            'Exception' => 'bad request'
        ];
        $configExceptionDefault = [
            'status'  => $statusCode,
            'code'    => $code,
            'message' => $message,
            'headers' => $headers,
        ];
        $errors = [
            'name' => [
                'This field should be not blank.',
                'This value is too long. It should have 30 character or less.'
            ]
        ];
        $data = [
            'error' => [
                'status'      => $statusCode,
                'code'        => $code,
                'message'     => $message,
                'errors'      => $errors
            ]
        ];

        $this
            ->given(
                $exception         = $this->getFormValidationExceptionMock($errors),
                $kernel            = $this->getKernelMock(),
                $exceptionManager  = $this->getManagerException($configExceptionDefault),
                $request           = $this->getRequestMock('/test-uri/', 'GET'),
                $event             = $this->getGetResponseForExceptionEventMock($request, $exception),
                $exceptionListener = new TestedClass($kernel, $exceptionManager, $matchAll, $configExceptionDefault),
                $exceptionListener->onKernelException($event),
                $validResponse     = new JsonResponse($data, $statusCode, $headers)
            )
            ->then
            ->mock($event)
                ->call('setResponse')
                    ->withArguments($validResponse)
                    ->once()
        ;
    }

    public function testOnKernelExceptionWithoutMatchAllAndExceptionNotMatch()
    {
        $matchAll = false;
        $code     = 1000;
        $message  = 'This is an exception for test listener';
        $configExceptionDefault = [
            'code'    => $code,
            'message' => $message,
        ];

        $this
            ->given(
                $exception         = new \Exception($message, $code),
                $kernel            = $this->getKernelMock(),
                $exceptionManager  = $this->getManagerException($configExceptionDefault),
                $request           = $this->getRequestMock('/test-uri/', 'GET'),
                $event             = $this->getGetResponseForExceptionEventMock($request, $exception),
                $exceptionListener = new TestedClass($kernel, $exceptionManager, $matchAll, $configExceptionDefault),
                $exceptionListener->onKernelException($event)
            )
            ->then
                ->mock($event)
                    ->call('setResponse')
                        ->never()
        ;
    }

    public function testOnKernelExceptionWithMatchAllAndConfigExceptionSameDefaultAndVariablesInMessage()
    {
        $type       = 'notification';
        $matchAll   = true;
        $statusCode = 400;
        $code       = 1000;
        $message    = 'Type {Type_1} not found';
        $headers    = [];
        $configExceptionDefault = [
            'status'  => $statusCode,
            'code'    => $code,
            'message' => $message,
            'headers' => $headers,
        ];
        $data = [
            'error' => [
                'status'  => $statusCode,
                'code'    => $code,
                'message' => 'Type '.$type.' not found',
            ]
        ];

        $this
            ->given(
                $exception         = new TypeNotFoundHttpException($type),
                $kernel            = $this->getKernelMock(),
                $exceptionManager  = $this->getManagerException($configExceptionDefault),
                $request           = $this->getRequestMock('/test-uri/', 'GET'),
                $event             = $this->getGetResponseForExceptionEventMock($request, $exception),
                $exceptionListener = new TestedClass($kernel, $exceptionManager, $matchAll, $configExceptionDefault, false),
                $exceptionListener->onKernelException($event),
                $validResponse     = new JsonResponse($data, $statusCode, $headers)
            )
            ->then
                ->mock($event)
                    ->call('setResponse')
                        ->withArguments($validResponse)
                        ->once()
        ;
    }
}