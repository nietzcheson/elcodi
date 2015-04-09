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

namespace Elcodi\Component\Sitemap\Transformer;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Elcodi\Component\Sitemap\Transformer\Interfaces\SitemapTransformerInterface;

/**
 * Class StaticRouteTransformer
 */
class StaticRouteTransformer implements SitemapTransformerInterface
{
    /**
     * @var UrlGeneratorInterface
     *
     * Url generator
     */
    protected $router;

    /**
     * Construct
     *
     * @param UrlGeneratorInterface $router Router
     */
    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    /**
     * Get url given an entity
     *
     * @param Mixed  $element  Element
     * @param string $language Language
     *
     * @return string url
     */
    public function getLoc($element, $language = null)
    {
        return $this
            ->router
            ->generate($element, [
                '_locale' => $language,
            ], true);
    }

    /**
     * Get last mod
     *
     * @param Mixed  $element  Element
     * @param string $language Language
     *
     * @return string Last mod value
     */
    public function getLastMod($element, $language = null)
    {
        return;
    }
}
