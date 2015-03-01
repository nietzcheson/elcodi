<?php

/*
 * This file is part of the Elcodi package.
 *
 * Copyright (c) 2014 Elcodi.com
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

namespace Elcodi\Component\Core\Services;

/**
 * Class RepositoryProvider
 */
class Slugify
{
    /**
     * Transform a text to an slug.
     *
     * @param  string $text The text to convert.
     *
     * @return string The slug.
     */
    public function transform($text)
    {
        // Replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // Trim
        $text = trim($text, '-');

        // Transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // Lowercase
        $text = strtolower($text);

        // Remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text)) {
            return '';
        }

        return $text;
    }
}
