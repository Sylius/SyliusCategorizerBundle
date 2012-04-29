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
 * Category loader interface.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
interface CategoryLoaderInterface
{
    /**
     * Loads category items.
     *
     * @param CategoryInterface $category
     *
     * @return array|Traversable
     */
    function loadCategory(CategoryInterface $category);
}
