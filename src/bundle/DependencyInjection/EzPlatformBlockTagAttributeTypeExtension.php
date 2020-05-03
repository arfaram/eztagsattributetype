<?php

declare(strict_types=1);

namespace EzPlatform\BlockTagAttributeTypeBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class EzPlatformBlockTagAttributeTypeExtension extends Extension implements PrependExtensionInterface
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
    }

    /** @param \Symfony\Component\DependencyInjection\ContainerBuilder $container */
    public function prepend(ContainerBuilder $container)
    {
        $container->prependExtensionConfig(
            'twig',
            [
                'form_themes' => [
                        '@EzPlatformBlockTagAttributeType/field/tags_field.html.twig',
                    ],
            ]
        );
    }
}
