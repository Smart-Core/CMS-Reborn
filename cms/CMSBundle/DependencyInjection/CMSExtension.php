<?php

declare(strict_types=1);

namespace SmartCore\CMSBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class CMSExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        //$this->createCacheService($container, $config['cache_provider']);

        //$container->setParameter('cms.design', $config['design']);
    }

    /**
     * @param ContainerBuilder $container
     * @param string           $cache_proviver_id
     */
    protected function createCacheService(ContainerBuilder $container, string $cache_proviver_id): void
    {
        $definition = new Definition(
            'SmartCore\\CMSBundle\\Cache\\CmsCacheProvider', [
                new Reference($cache_proviver_id),
            ]
        );

        $definition->setPublic(true);

        $container->setDefinition('cms.cache', $definition);
    }
}
