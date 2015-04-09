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

namespace Elcodi\Component\Sitemap\Profile;

use Elcodi\Component\Sitemap\Builder\SitemapBuilder;

/**
 * Class SitemapProfile
 */
class SitemapProfile
{
    /**
     * @var array
     *
     * Languages
     */
    protected $languages;

    /**
     * @var SitemapBuilder[]
     *
     * Array of SitemapBuilders
     */
    protected $sitemapBuilders;

    /**
     * Construct
     *
     * @var array $languages Languages
     */
    public function __construct(array $languages = null)
    {
        $this->languages = $languages;
    }

    /**
     * Add a sitemapBuilder
     *
     * @param SitemapBuilder $sitemapBuilder Sitemap Builder
     *
     * @return $this Self object
     */
    public function addSitemapBuilder(SitemapBuilder $sitemapBuilder)
    {
        $this->sitemapBuilders[] = $sitemapBuilder;

        return $this;
    }

    /**
     * Build full profile
     *
     * @return $this Self object
     */
    public function build()
    {
        foreach ($this->sitemapBuilders as $sitemapBuilder) {
            if (is_array($this->languages)) {
                foreach ($this->languages as $language) {
                    $sitemapBuilder->build($language);
                }
            } else {
                $sitemapBuilder->build();
            }
        }

        return $this;
    }
}
