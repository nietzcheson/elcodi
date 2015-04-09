<?php

/*
 * This file is part of the Elcodi package.
 *
 * Copyright (c) 2014-2015 Elcodi.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @author Aldo Chiecchia <zimage@tiscali.it>
 * @author Elcodi Team <tech@elcodi.com>
 */

namespace Elcodi\Bundle\SitemapBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

use Elcodi\Bundle\CoreBundle\DependencyInjection\Abstracts\AbstractExtension;

/**
 * Class ElcodiSitemapExtension
 */
class ElcodiSitemapExtension extends AbstractExtension
{
    /**
     * @var string
     *
     * Extension name
     */
    const EXTENSION_NAME = 'elcodi_sitemap';

    /**
     * Get the Config file location
     *
     * @return string Config file location
     */
    public function getConfigFilesLocation()
    {
        return __DIR__ . '/../Resources/config';
    }

    /**
     * Return a new Configuration instance.
     *
     * If object returned by this method is an instance of
     * ConfigurationInterface, extension will use the Configuration to read all
     * bundle config definitions.
     *
     * Also will call getParametrizationValues method to load some config values
     * to internal parameters.
     *
     * @return ConfigurationInterface Configuration file
     */
    protected function getConfigurationInstance()
    {
        return new Configuration(static::EXTENSION_NAME);
    }

    /**
     * Load Parametrization definition
     *
     * return array(
     *      'parameter1' => $config['parameter1'],
     *      'parameter2' => $config['parameter2'],
     *      ...
     * );
     *
     * @param array $config Bundles config values
     *
     * @return array Parametrization values
     */
    protected function getParametrizationValues(array $config)
    {
        return [];
    }

    /**
     * Hook after load the full container
     *
     * @param array            $config    Configuration
     * @param ContainerBuilder $container Container
     */
    protected function postLoad(array $config, ContainerBuilder $container)
    {
        $this
            ->loadBlocks($config, $container)
            ->loadStatics($config, $container)
            ->loadBuilders($config, $container)
            ->loadProfiles($config, $container);
    }

    /**
     * Load blocks
     *
     * @param array            $config    Configuration
     * @param ContainerBuilder $container Container
     *
     * @return $this self Object
     */
    protected function loadBlocks(array $config, ContainerBuilder $container)
    {
        $blocks = $config['blocks'];

        foreach ($blocks as $blockName => $block) {
            $container
                ->register(
                    'elcodi.sitemap_element_generator.entity_' . $blockName,
                    'Elcodi\Component\Sitemap\Element\EntitySitemapElementGenerator'
                )
                ->addArgument(new Reference('elcodi.factory.sitemap_element'))
                ->addArgument(new Reference($block['transformer']))
                ->addArgument(new Reference($block['repository_service']))
                ->addArgument($block['method'])
                ->addArgument($block['arguments'])
                ->addArgument($block['changeFrequency'])
                ->addArgument($block['priority'])
                ->setPublic(false);
        }

        return $this;
    }

    /**
     * Load statics
     *
     * @param array            $config    Configuration
     * @param ContainerBuilder $container Container
     *
     * @return $this self Object
     */
    protected function loadStatics(array $config, ContainerBuilder $container)
    {
        $statics = $config['statics'];

        foreach ($statics as $staticName => $static) {
            $container
                ->register(
                    'elcodi.sitemap_element_generator.static_' . $staticName,
                    'Elcodi\Component\Sitemap\Element\StaticSitemapElementGenerator'
                )
                ->addArgument(new Reference('elcodi.factory.sitemap_element'))
                ->addArgument(new Reference($static['transformer']))
                ->addArgument($staticName)
                ->addArgument($static['changeFrequency'])
                ->addArgument($static['priority'])
                ->setPublic(false);
        }

        return $this;
    }

    /**
     * Load builders
     *
     * @param array            $config    Configuration
     * @param ContainerBuilder $container Container
     *
     * @return $this self Object
     */
    protected function loadBuilders(array $config, ContainerBuilder $container)
    {
        $builders = $config['builder'];

        foreach ($builders as $builderName => $builder) {
            $definition = $container
                ->register(
                    'elcodi.sitemap_builder.' . $builderName,
                    'Elcodi\Component\Sitemap\Builder\SitemapBuilder'
                )
                ->addArgument(new Reference($builder['renderer']))
                ->addArgument($builder['path'])
                ->setPublic(true);

            foreach ($builder['blocks'] as $blockReference) {
                $definition->addMethodCall(
                    'addSitemapElementGenerator',
                    [new Reference('elcodi.sitemap_element_generator.entity_' . $blockReference)]
                );
            }

            foreach ($builder['statics'] as $staticReference) {
                $definition->addMethodCall(
                    'addSitemapElementGenerator',
                    [new Reference('elcodi.sitemap_element_generator.static_' . $staticReference)]
                );
            }
        }

        return $this;
    }

    /**
     * Load profiles
     *
     * @param array            $config    Configuration
     * @param ContainerBuilder $container Container
     *
     * @return $this self Object
     */
    protected function loadProfiles(array $config, ContainerBuilder $container)
    {
        $profiles = $config['profile'];

        foreach ($profiles as $profileName => $profile) {
            $definition = $container
                ->register(
                    'elcodi.sitemap_profile.' . $profileName,
                    'Elcodi\Component\Sitemap\Profile\SitemapProfile'
                )
                ->addArgument(new Reference($profile['languages']))
                ->setPublic(true);

            foreach ($profile['builders'] as $builderReference) {
                $definition->addMethodCall(
                    'addSitemapBuilder',
                    [new Reference('elcodi.sitemap_builder.' . $builderReference)]
                );
            }
        }

        return $this;
    }

    /**
     * Config files to load
     *
     * @param array $config Configuration
     *
     * @return array Config files
     */
    public function getConfigFiles(array $config)
    {
        return [
            'classes',
            'renderers',
            'dumperChain',
            'eventDispatchers',
            'commands',
            'sitemapTransformers',
            'factories',
        ];
    }

    /**
     * Returns the extension alias, same value as extension name
     *
     * @return string The alias
     */
    public function getAlias()
    {
        return static::EXTENSION_NAME;
    }
}
