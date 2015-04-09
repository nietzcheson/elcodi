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

namespace Elcodi\Component\Sitemap\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Elcodi\Component\Sitemap\Builder\SitemapBuilder;
use Elcodi\Component\Sitemap\Exception\SitemapBuilderNotFoundException;

/**
 * Class SitemapBuildCommand
 */
class SitemapBuildCommand extends Command
{
    /**
     * @var ContainerInterface
     *
     * Container
     */
    protected $container;

    /**
     * Construct
     *
     * @param ContainerInterface $container Container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct();
    }

    /**
     * configure
     */
    protected function configure()
    {
        $this
            ->setName('elcodi:sitemap:build')
            ->setDescription('Build a sitemap')
            ->addArgument(
                'builder-name',
                InputArgument::REQUIRED,
                'Builder name'
            )
            ->addOption(
                'language',
                null,
                InputOption::VALUE_OPTIONAL,
                'Language'
            );
    }

    /**
     * Get the dumper name from the input object and tries to dump all sitemap
     * content
     *
     * @param InputInterface  $input  The input interface
     * @param OutputInterface $output The output interface
     *
     * @return integer|null|void
     *
     * @throws SitemapBuilderNotFoundException Profile not found
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $builderName = $input->getArgument('builder-name');
        $language = $input->getOption('language');
        $sitemapBuilder = $this
            ->container
            ->get('elcodi.sitemap_builder.' . $builderName);

        if (!($sitemapBuilder instanceof SitemapBuilder)) {
            throw new SitemapBuilderNotFoundException(
                sprintf('Sitemap builder "%s" not found', $builderName)
            );
        }

        $sitemapBuilder->build($language);
    }
}
