<?php
namespace M6Web\Bundle\ApiExceptionBundle\Tests\Units\DependencyInjection;

use atoum\test;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use M6Web\Bundle\ApiExceptionBundle\DependencyInjection\M6WebApiExceptionExtension as TestedClass;

class M6WebApiExceptionExtension extends test
{
    protected function registerServiceKernel($container)
    {
        $definition = new Definition(
            'Symfony\Component\HttpKernel\Kernel',
            [
                'dev',
                true,
            ]
        );
        $container->setDefinition('kernel', $definition);

        return $container;
    }

    protected function getContainerForConfiguration($fixtureName)
    {
        $parameterBag = new ParameterBag(array('kernel.debug' => true));
        $container = new ContainerBuilder($parameterBag);

        $container = $this->registerServiceKernel($container);

        $extension = new TestedClass();
        $container->registerExtension($extension);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../Fixtures/config'));
        $loader->load($fixtureName.'.yml');

        return $container;
    }

    /**
     * @php < 7.1
     */
    public function testDefaultConfiguration()
    {
        $container = $this->getContainerForConfiguration('config');
        $container->compile();

        $this
            ->boolean($container->has('m6web_api_exception.manager.exception'))
                ->isTrue()
            ->array($managerArguments = $container->getDefinition('m6web_api_exception.manager.exception')->getArguments())
                ->hasSize(2)
                ->array($managerArguments[0])
                    ->hasSize(4)
                    ->integer($managerArguments[0]['status'])
                        ->isEqualTo(500)
                    ->integer($managerArguments[0]['code'])
                        ->isEqualTo(0)
                    ->string($managerArguments[0]['message'])
                        ->isEqualTo('Internal server error')
                    ->array($managerArguments[0]['headers'])
                        ->isEqualTo([])
                ->array($managerArguments[1])
                    ->hasSize(0)
            ->array($listenerArguments = $container->getDefinition('m6web_api_exception.listener.exception')->getArguments())
                ->hasSize(5)
                ->object($listenerArguments[0])
                    ->isInstanceOf('Symfony\Component\DependencyInjection\Reference')
                ->string((string) $listenerArguments[0])
                    ->isEqualTo('kernel')
                ->object($listenerArguments[1])
                    ->isInstanceOf('Symfony\Component\DependencyInjection\Reference')
                ->string((string) $listenerArguments[1])
                    ->isEqualTo('m6web_api_exception.manager.exception')
                ->boolean($listenerArguments[2])
                    ->isEqualTo(true)
                ->array($listenerArguments[3])
                    ->hasSize(4)
                    ->integer($managerArguments[0]['status'])
                        ->isEqualTo(500)
                    ->integer($managerArguments[0]['code'])
                        ->isEqualTo(0)
                    ->string($managerArguments[0]['message'])
                        ->isEqualTo('Internal server error')
                    ->array($managerArguments[0]['headers'])
                        ->isEqualTo([])
                ->boolean($listenerArguments[4])
                    ->isEqualTo(false)
        ;
    }

    /**
     * @php < 7.1
     */
    public function testCustomConfiguration()
    {
        $container = $this->getContainerForConfiguration('config_custom');
        $container->compile();

        $this
            ->boolean($container->has('m6web_api_exception.manager.exception'))
                ->isTrue()
            ->array($managerArguments = $container->getDefinition('m6web_api_exception.manager.exception')->getArguments())
                ->hasSize(2)
                ->array($managerArguments[0])
                    ->hasSize(4)
                    ->integer($managerArguments[0]['status'])
                        ->isEqualTo(400)
                    ->integer($managerArguments[0]['code'])
                        ->isEqualTo(1000)
                    ->string($managerArguments[0]['message'])
                        ->isEqualTo('Internal server error')
                    ->array($managerArguments[0]['headers'])
                        ->isEqualTo([])
                ->array($managerArguments[1])
                    ->hasSize(2)
                    ->array($managerArguments[1]['M6Web\Bundle\ApiExceptionBundle\Exception\ValidationFormException'])
                        ->hasSize(3)
                        ->integer($managerArguments[1]['M6Web\Bundle\ApiExceptionBundle\Exception\ValidationFormException']['code'])
                            ->isEqualTo(1001)
                        ->string($managerArguments[1]['M6Web\Bundle\ApiExceptionBundle\Exception\ValidationFormException']['message'])
                            ->isEqualTo('form validation failed')
                        ->array($managerArguments[1]['M6Web\Bundle\ApiExceptionBundle\Exception\ValidationFormException']['headers'])
                            ->hasSize(1)
                            ->string($managerArguments[1]['M6Web\Bundle\ApiExceptionBundle\Exception\ValidationFormException']['headers']['Exception'])
                                ->isEqualTo('form validation failed')
                    ->array($managerArguments[1]['M6Web\Bundle\ApiExceptionBundle\Tests\Fixtures\Exception\TypeNotFoundException'])
                        ->hasSize(4)
                        ->integer($managerArguments[1]['M6Web\Bundle\ApiExceptionBundle\Tests\Fixtures\Exception\TypeNotFoundException']['status'])
                            ->isEqualTo(404)
                        ->integer($managerArguments[1]['M6Web\Bundle\ApiExceptionBundle\Tests\Fixtures\Exception\TypeNotFoundException']['code'])
                            ->isEqualTo(1002)
                        ->string($managerArguments[1]['M6Web\Bundle\ApiExceptionBundle\Tests\Fixtures\Exception\TypeNotFoundException']['message'])
                            ->isEqualTo('type {type} not found')
                        ->array($managerArguments[1]['M6Web\Bundle\ApiExceptionBundle\Tests\Fixtures\Exception\TypeNotFoundException']['headers'])
                            ->isEqualTo([])
            ->array($listenerArguments = $container->getDefinition('m6web_api_exception.listener.exception')->getArguments())
                ->hasSize(5)
                ->object($listenerArguments[0])
                    ->isInstanceOf('Symfony\Component\DependencyInjection\Reference')
                ->string((string) $listenerArguments[0])
                    ->isEqualTo('kernel')
                ->object($listenerArguments[1])
                    ->isInstanceOf('Symfony\Component\DependencyInjection\Reference')
                ->string((string) $listenerArguments[1])
                    ->isEqualTo('m6web_api_exception.manager.exception')
                ->boolean($listenerArguments[2])
                    ->isEqualTo(false)
                ->array($listenerArguments[3])
                    ->hasSize(4)
                    ->integer($managerArguments[0]['status'])
                        ->isEqualTo(400)
                    ->integer($managerArguments[0]['code'])
                        ->isEqualTo(1000)
                    ->string($managerArguments[0]['message'])
                        ->isEqualTo('Internal server error')
                    ->array($managerArguments[0]['headers'])
                        ->isEqualTo([])
                ->boolean($listenerArguments[4])
                    ->isEqualTo(true)
        ;
    }

    /**
     * @php >= 7.1
     */
    public function testDefaultConfigurationPhp71Min()
    {
        $container = $this->getContainerForConfiguration('config');
        $container->compile();

        $this
            ->boolean($container->has('m6web_api_exception.manager.exception'))
                ->isTrue()
            ->array($managerArguments = $container->getDefinition('m6web_api_exception.manager.exception')->getArguments())
                ->hasSize(2)
                ->array($managerArguments[0])
                    ->hasSize(4)
                    ->integer($managerArguments[0]['status'])
                        ->isEqualTo(500)
                    ->integer($managerArguments[0]['code'])
                        ->isEqualTo(0)
                    ->string($managerArguments[0]['message'])
                        ->isEqualTo('Internal server error')
                    ->array($managerArguments[0]['headers'])
                        ->isEqualTo([])
                ->array($managerArguments[1])
                    ->hasSize(0)
            ->array($listenerArguments = $container->getDefinition('m6web_api_exception.listener.exception')->getArguments())
                ->hasSize(5)
                ->object($listenerArguments[0])
                    ->isInstanceOf('Symfony\Component\DependencyInjection\Definition')
                ->string($listenerArguments[0]->getClass())
                    ->isEqualTo('Symfony\Component\HttpKernel\Kernel')
                ->object($listenerArguments[1])
                    ->isInstanceOf('Symfony\Component\DependencyInjection\Reference')
                ->string((string) $listenerArguments[1])
                    ->isEqualTo('m6web_api_exception.manager.exception')
                ->boolean($listenerArguments[2])
                    ->isEqualTo(true)
                ->array($listenerArguments[3])
                    ->hasSize(4)
                    ->integer($managerArguments[0]['status'])
                        ->isEqualTo(500)
                    ->integer($managerArguments[0]['code'])
                        ->isEqualTo(0)
                    ->string($managerArguments[0]['message'])
                        ->isEqualTo('Internal server error')
                    ->array($managerArguments[0]['headers'])
                        ->isEqualTo([])
                ->boolean($listenerArguments[4])
                    ->isEqualTo(false)
        ;
    }

    /**
     * @php >= 7.1
     */
    public function testCustomConfigurationPhp71Min()
    {
        $container = $this->getContainerForConfiguration('config_custom');
        $container->compile();

        $this
            ->boolean($container->has('m6web_api_exception.manager.exception'))
                ->isTrue()
            ->array($managerArguments = $container->getDefinition('m6web_api_exception.manager.exception')->getArguments())
                ->hasSize(2)
                ->array($managerArguments[0])
                    ->hasSize(4)
                    ->integer($managerArguments[0]['status'])
                        ->isEqualTo(400)
                    ->integer($managerArguments[0]['code'])
                        ->isEqualTo(1000)
                    ->string($managerArguments[0]['message'])
                        ->isEqualTo('Internal server error')
                    ->array($managerArguments[0]['headers'])
                        ->isEqualTo([])
                ->array($managerArguments[1])
                    ->hasSize(2)
                    ->array($managerArguments[1]['M6Web\Bundle\ApiExceptionBundle\Exception\ValidationFormException'])
                        ->hasSize(3)
                        ->integer($managerArguments[1]['M6Web\Bundle\ApiExceptionBundle\Exception\ValidationFormException']['code'])
                            ->isEqualTo(1001)
                        ->string($managerArguments[1]['M6Web\Bundle\ApiExceptionBundle\Exception\ValidationFormException']['message'])
                            ->isEqualTo('form validation failed')
                        ->array($managerArguments[1]['M6Web\Bundle\ApiExceptionBundle\Exception\ValidationFormException']['headers'])
                            ->hasSize(1)
                            ->string($managerArguments[1]['M6Web\Bundle\ApiExceptionBundle\Exception\ValidationFormException']['headers']['Exception'])
                                ->isEqualTo('form validation failed')
                    ->array($managerArguments[1]['M6Web\Bundle\ApiExceptionBundle\Tests\Fixtures\Exception\TypeNotFoundException'])
                        ->hasSize(4)
                        ->integer($managerArguments[1]['M6Web\Bundle\ApiExceptionBundle\Tests\Fixtures\Exception\TypeNotFoundException']['status'])
                            ->isEqualTo(404)
                        ->integer($managerArguments[1]['M6Web\Bundle\ApiExceptionBundle\Tests\Fixtures\Exception\TypeNotFoundException']['code'])
                            ->isEqualTo(1002)
                        ->string($managerArguments[1]['M6Web\Bundle\ApiExceptionBundle\Tests\Fixtures\Exception\TypeNotFoundException']['message'])
                            ->isEqualTo('type {type} not found')
                        ->array($managerArguments[1]['M6Web\Bundle\ApiExceptionBundle\Tests\Fixtures\Exception\TypeNotFoundException']['headers'])
                            ->isEqualTo([])
            ->array($listenerArguments = $container->getDefinition('m6web_api_exception.listener.exception')->getArguments())
                ->hasSize(5)
                ->object($listenerArguments[0])
                    ->isInstanceOf('Symfony\Component\DependencyInjection\Definition')
                ->string($listenerArguments[0]->getClass())
                    ->isEqualTo('Symfony\Component\HttpKernel\Kernel')
                ->object($listenerArguments[1])
                    ->isInstanceOf('Symfony\Component\DependencyInjection\Reference')
                ->string((string) $listenerArguments[1])
                    ->isEqualTo('m6web_api_exception.manager.exception')
                ->boolean($listenerArguments[2])
                    ->isEqualTo(false)
                ->array($listenerArguments[3])
                    ->hasSize(4)
                    ->integer($managerArguments[0]['status'])
                        ->isEqualTo(400)
                    ->integer($managerArguments[0]['code'])
                        ->isEqualTo(1000)
                    ->string($managerArguments[0]['message'])
                        ->isEqualTo('Internal server error')
                    ->array($managerArguments[0]['headers'])
                        ->isEqualTo([])
                ->boolean($listenerArguments[4])
                    ->isEqualTo(true)
        ;
    }
}
