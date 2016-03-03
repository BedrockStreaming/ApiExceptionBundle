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

        $this->loadExceptionManager($container, $config);
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
     * load service exception manager
     *
     * @param ContainerBuilder $container
     * @param array            $config
     */
    protected function loadExceptionManager(ContainerBuilder $container, array $config)
    {
        $definition = new Definition(
            'M6Web\Bundle\ApiExceptionBundle\Manager\ExceptionManager',
            [
                $config['default'],
                $config['exceptions'],
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
        $definition = new Definition(
            'M6Web\Bundle\ApiExceptionBundle\EventListener\ExceptionListener',
            [
                new Reference('kernel'),
                new Reference($this->getAlias().'.manager.exception'),
                $config['match_all'],
                $config['default'],
                $config['stack_trace'],
            ]
        );

        $definition->addTag(
            'kernel.event_listener',
            [
                'event' => 'kernel.exception',
                'method' => 'onKernelException',
                'priority' => '-100' // as the setresponse stop the exception propagation, this listener has to pass in last position
            ]
        );

        $container->setDefinition($this->getAlias().'.listener.exception', $definition);
    }
}
