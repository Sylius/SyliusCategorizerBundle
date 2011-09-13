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
     * @return CategoryInterface
     */
    function createCategory();

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
     * @param integer $id
     * @return CategoryInterface
     */
    function findCategory($id);
    
    /**
     * Finds category by criteria.
     * 
     * @param array $criteria
     * @return CategoryInterface
     */
    function findCategoryBy(array $criteria);
    
    /**
     * Finds all categories.
     * 
     * @return array
     */
    function findCategories();
    
    /**
     * Finds categories by criteria.
     * 
     * @param array $criteria
     * @return array
     */
    function findCategoriesBy(array $criteria);
    
    /**
     * Returns FQCN of category.
     * 
     * @return string
     */
    function getClass();
}
