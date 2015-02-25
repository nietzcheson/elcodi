<?php

/*
 * This file is part of the Elcodi package.
 *
 * Copyright (c) 2015 Project
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author
 */

namespace Elcodi\Plugin\PaymillBundle\DependencyInjection;

use Elcodi\Bundle\CoreBundle\DependencyInjection\Abstracts\AbstractExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;

class ElcodiPaymillExtension extends AbstractExtension
{
    /**
     * @var string
     *
     * Extension name
     */
    const EXTENSION_NAME = 'elcodi_paymill';

    /**
     * Get the Config file location
     *
     * @return string Config file location
     */
    public function getConfigFilesLocation()
    {
        return __DIR__.'/../Resources/config';
    }

    /**
     * Config files to load
     *
     * @param array $config Config array
     *
     * @return array Config files
     */
    public function getConfigFiles(array $config)
    {
        return [
            'templating'
        ];
    }

    /**
     * @param ContainerBuilder $container
     */
    public function prepend(ContainerBuilder $container)
    {
        $locator = new FileLocator($this->getConfigFilesLocation());
        $file = $locator->locate('configuration.yml');

        if (!file_exists($file)) {
            throw new \InvalidArgumentException(sprintf('The file "%s" is not valid.', $file));
        }

        $yamlParser = new Parser();
        $content = file_get_contents($file);
        $configuration = $yamlParser->parse($content);

        $configuration = $configuration['elcodi_configuration'];

        $bundles = $container->getParameter('kernel.bundles');

        if (isset($bundles['ElcodiConfigurationBundle'])) {
            $container->prependExtensionConfig('elcodi_configuration', $configuration);

            var_dump($container->getExtensionConfig('elcodi_configuration'));
        }
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