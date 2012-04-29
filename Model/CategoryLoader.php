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

use Sylius\Bundle\CategorizerBundle\Registry\CatalogRegistry;

/**
 * Loads category items.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
abstract class CategoryLoader implements CategoryLoaderInterface
{
    /**
     * Catalog registry.
     *
     * @var CatalogRegistry
     */
    protected $catalogRegistry;

    /**
     * Constructor.
     *
     * @param CatalogRegistry $catalogRegistry
     */
    public function __construct(CatalogRegistry $catalogRegistry)
    {
        $this->catalogRegistry = $catalogRegistry;
    }
}
