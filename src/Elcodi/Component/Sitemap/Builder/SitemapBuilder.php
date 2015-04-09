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

namespace Elcodi\Component\Sitemap\Builder;

use Elcodi\Component\Sitemap\Element\Interfaces\SitemapElementGeneratorInterface;
use Elcodi\Component\Sitemap\Renderer\Interfaces\SitemapRendererInterface;

/**
 * Class SitemapBuilder
 */
class SitemapBuilder
{
    /**
     * @var SitemapElementGeneratorInterface[]
     *
     * sitemapElementGenerators
     */
    protected $sitemapElementGenerators;

    /**
     * @var SitemapRendererInterface
     *
     * sitemapRenderer
     */
    protected $sitemapRenderer;

    /**
     * @var string
     *
     * path
     */
    protected $path;

    /**
     * Construct
     *
     * @param SitemapRendererInterface $sitemapRenderer sitemapRenderer
     * @param string                   $path            Path
     */
    public function __construct(
        SitemapRendererInterface $sitemapRenderer,
        $path
    ) {
        $this->sitemapRenderer = $sitemapRenderer;
        $this->path = $path;
    }

    /**
     * Add a new SitemapElement Generator
     *
     * @param SitemapElementGeneratorInterface $sitemapElementGenerator sitemapRenderer
     *
     * @return $this
     */
    public function addSitemapElementGenerator(
        SitemapElementGeneratorInterface $sitemapElementGenerator
    ) {
        $this->sitemapElementGenerators[] = $sitemapElementGenerator;

        return $this;
    }

    /**
     * Build sitemap builder
     *
     * @param string|null $language Language
     *
     * @return string Generated data
     */
    public function build($language = null)
    {
        $sitemapElements = [];

        /**
         * @var SitemapElementGeneratorInterface $sitemapElementGenerator
         */
        foreach ($this->sitemapElementGenerators as $sitemapElementGenerator) {
            $sitemapElements = array_merge(
                $sitemapElements,
                $sitemapElementGenerator->generateElements($language)
            );
        }

        $data = $this
            ->sitemapRenderer
            ->render($sitemapElements);

        file_put_contents(
            $this->resolvePathWithLanguage(
                $this->path,
                $language
            ),
            $data
        );

        return $data;
    }

    /**
     * Given a language and a path, resolve this path
     *
     * @param string $path     Path
     * @param string $language Language
     *
     * @return string Path resolved
     */
    protected function resolvePathWithLanguage($path, $language)
    {
        return str_replace('{_locale}', $language, $path);
    }
}
