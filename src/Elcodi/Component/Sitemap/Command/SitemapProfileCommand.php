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
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Elcodi\Component\Sitemap\Exception\SitemapProfileNotFoundException;
use Elcodi\Component\Sitemap\Profile\SitemapProfile;

/**
 * Class SitemapProfileCommand
 */
class SitemapProfileCommand extends Command
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
            ->setName('elcodi:sitemap:profile')
            ->setDescription('Build a profile')
            ->addArgument(
                'profile-name',
                InputArgument::REQUIRED,
                'Profile name'
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
     * @throws SitemapProfileNotFoundException Profile not found
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $profileName = $input->getArgument('profile-name');
        $sitemapProfile = $this
            ->container
            ->get('elcodi.sitemap_profile.' . $profileName);

        if (!($sitemapProfile instanceof SitemapProfile)) {
            throw new SitemapProfileNotFoundException(
                sprintf('Sitemap profile "%s" not found', $sitemapProfile)
            );
        }

        $sitemapProfile->build();
    }
}
