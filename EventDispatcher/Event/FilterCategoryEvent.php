<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CategorizerBundle\EventDispatcher\Event;

use Sylius\Bundle\CategorizerBundle\Model\CategoryInterface;
use Sylius\Bundle\CategorizerBundle\Registry\CatalogInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Filter category event.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class FilterCategoryEvent extends Event
{
    /**
     * Category object.
     *
     * @var CategoryInterface
     */
    private $category;

    /**
     * Catalog.
     *
     * @var CatalogInterface
     */
    private $catalog;

    /**
     * @param CategoryInterface $category
     * @param CatalogInterface  $catalog
     */
    public function __construct(CategoryInterface $category, CatalogInterface $catalog)
    {
        $this->category = $category;
        $this->catalog = $catalog;
    }

    /**
     * Returns category.
     *
     * @return CategoryInterface
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Returns catalog.
     *
     * @return CatalogInterface
     */
    public function getCatalog()
    {
        return $this->catalog;
    }
}
