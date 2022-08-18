<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class LingodaBrazeExtension extends ConfigurableExtension
{
    /**
     * @param array<string, mixed> $mergedConfig
     *
     * @throws \Exception
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('braze.xml');

        $defaultClient = 'lingoda_braze.default_http_client';
        $definition = $container->getDefinition($defaultClient);
        $definition->replaceArgument(0, $mergedConfig['api_key']);
        $definition->replaceArgument(1, $mergedConfig['base_uri']);
        $definition->replaceArgument(2, $mergedConfig['http_client_max_retries']);

        $referenceId = $mergedConfig['http_client'] ?? $defaultClient;
        $definition = $container->getDefinition('lingoda_braze.client');
        $definition->replaceArgument(3, new Reference($referenceId));
    }
}
