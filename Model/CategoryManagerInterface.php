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
 * Category manager interface.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
interface CategoryManagerInterface
{
    /**
     * Creates new category object.
     *
     * @param string|CatalogInterface $catalog
     *
     * @return CategoryInterface
     */
    function createCategory($catalog);

    /**
     * Returns paginator instance for given category.
     *
     * @param CategoryInterface $category
     * @param Closure           $callback
     */
    function createPaginator(CategoryInterface $category, \Closure $callback = null);

    /**
     * Persist category.
     *
     * @param CategoryInterface
     */
    function persistCategory(CategoryInterface $category);

    /**
     * Removes category.
     *
     * @param CategoryInterface $category
     */
    function removeCategory(CategoryInterface $category);

    /**
     * Finds category by id.
     *
     * @param integer                 $id
     * @param string|CatalogInterface $catalog
     *
     * @return CategoryInterface
     */
    function findCategory($id, $catalog);

    /**
     * Finds category by criteria.
     *
     * @param array                   $criteria
     * @param string|CatalogInterface $catalog
     *
     * @return CategoryInterface
     */
    function findCategoryBy(array $criteria, $catalog);

    /**
     * Finds all categories.
     *
     * @param string|CatalogInterface $catalog
     *
     * @return array
     */
    function findCategories($catalog);

    /**
     * Finds categories by criteria.
     *
     * @param array                   $criteria
     * @param string|CatalogInterface $catalog
     *
     * @return array
     */
    function findCategoriesBy(array $criteria, $catalog);

    /**
     * Moves category up in the list.
     *
     * @param CategoryInterface $category
     */
    function moveCategoryDown(CategoryInterface $category);

    /**
     * Moves category down in the list.
     *
     * @param CategoryInterface $category
     */
    function moveCategoryUp(CategoryInterface $category);
}
