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

namespace Elcodi\Component\Core\Generator\Interfaces;

/**
 * Interface GeneratorInterface
 *
 * This interface is for static generators.
 *
 * An static generator do not have any kind of customizable behaviour, public
 * implementable method called generate() do not accept any parameter, so
 * result should not depends on context
 */
interface GeneratorInterface
{
    /**
     * Generates a hash
     *
     * @param integer $length Length of generation
     *
     * @return string Generation
     */
    public function generate($length = null);
}
