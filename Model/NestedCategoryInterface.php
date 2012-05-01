<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CategorizerBundle\Model;

/**
 * Nested category model interface.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
interface NestedCategoryInterface
{
    /**
     * Get parent category.
     *
     * @return NestedCategoryInterface
     */
    function getParent();

    /**
     * Set parent category.
     *
     * @param NestedCategoryInterface $parent
     */
    function setParent(NestedCategoryInterface $parent);
}
