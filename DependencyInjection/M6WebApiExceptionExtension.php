<?php

namespace M6Web\Bundle\ApiExceptionBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * This is the class that loads and manages your bundle configuration
 */
class M6WebApiExceptionExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter($this->getAlias().'.logger.prefix', $config['logger']['prefix']);

        $this->loadLogger($container, $config['logger']);
        $this->loadExceptionManager($container, $config['exception']);
        $this->loadExceptionListener($container, $config);
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return 'm6web_api_exception';
    }

    /**
     * load service logger
     *
     * @param ContainerBuilder $container
     * @param array            $configLogger
     */
    protected function loadLogger(ContainerBuilder $container, array $configLogger)
    {
        $definition = new Definition(
            'M6Web\Bundle\ApiExceptionBundle\Logger\Logger',
            [
                new Reference('logger'),
                $configLogger['prefix'],
            ]
        );

        $container->setDefinition($this->getAlias().'.logger', $definition);
    }

    /**
     * load service exception manager
     *
     * @param ContainerBuilder $container
     * @param array            $configException
     */
    protected function loadExceptionManager(ContainerBuilder $container, array $configException)
    {
        $definition = new Definition(
            'M6Web\Bundle\ApiExceptionBundle\Manager\ExceptionManager',
            [
                $configException['default'],
                $configException['exceptions'],
            ]
        );

        $container->setDefinition($this->getAlias().'.manager.exception', $definition);
    }

    /**
     * load service exception listener
     *
     * @param ContainerBuilder $container
     * @param array            $config
     */
    protected function loadExceptionListener(ContainerBuilder $container, array $config)
    {
        if (is_null($config['logger']['service'])) {
            $config['logger']['service'] = $this->getAlias().'.logger';
        }

        $definition = new Definition(
            'M6Web\Bundle\ApiExceptionBundle\EventListener\ExceptionListener',
            [
                new Reference('kernel'),
                new Reference($this->getAlias().'.manager.exception'),
                new Reference($config['logger']['service']),
                $config['exception']['match_all'],
                $config['exception']['default'],
                $config['exception']['stack_trace'],
            ]
        );

        $definition->addTag(
            'kernel.event_listener',
            [
                'event' => 'kernel.exception',
                'method' => 'onKernelException',
            ]
        );

        $container->setDefinition($this->getAlias().'.listener.exception', $definition);
    }
}
