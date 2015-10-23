<?php
namespace M6Web\Bundle\ApiExceptionBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class bundle
 */
class M6WebApiExceptionBundle extends Bundle
{
    /**
     * @return DependencyInjection\M6WebApiExceptionExtension|null|\Symfony\Component\DependencyInjection\Extension\ExtensionInterface
     */
    public function getContainerExtension()
    {
        return new DependencyInjection\M6WebApiExceptionExtension();
    }
}
