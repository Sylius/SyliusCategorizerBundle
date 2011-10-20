<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CatalogBundle\Model;

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
     * @param CatalogInterface $catalog
     * @return CategoryInterface
     */
    function createCategory(CatalogInterface $catalog);

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
     * @param CatalogInterface $catalog
     * @param integer $id
     * @return CategoryInterface
     */
    function findCategory(CatalogInterface $catalog, $id);
    
    /**
     * Finds category by criteria.
     * 
     * @param CatalogInterface $catalog
     * @param array $criteria
     * @return CategoryInterface
     */
    function findCategoryBy(CatalogInterface $catalog, array $criteria);
    
    /**
     * Finds all categories.
     * 
     * @param CatalogInterface $catalog
     * @return array
     */
    function findCategories(CatalogInterface $catalog);
    
    /**
     * Finds categories by criteria.
     * 
     * @param CatalogInterface $catalog
     * @param array $criteria
     * @return array
     */
    function findCategoriesBy(CatalogInterface $catalog, array $criteria);
    
    /**
     * Returns paginator instance for given category.
     * 
     * @param CatalogInterface $catalog
     * @param CategoryInterface $category
     */
    function createPaginator(CatalogInterface $catalog, CategoryInterface $category, $sorter = null);
}
