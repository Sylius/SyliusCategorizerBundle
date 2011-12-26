<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CatalogBundle\Manipulator;

use Sylius\Bundle\CatalogBundle\Model\CatalogInterface;

use Sylius\Bundle\CatalogBundle\Model\CategoryInterface;

/**
 * Category manipulator interface.
 * 
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
interface CategoryManipulatorInterface
{
    /**
     * Creates a category.
     * 
     * @param CategoryInterface $category
     */
    function create(CategoryInterface $category);
    
    /**
     * Updates a category.
     * 
     * @param CategoryInterface $category
     */
    function update(CategoryInterface $category);  
    
    /**
     * Deletes a category.
     * 
     * @param CategoryInterface $category
     */
    function delete(CategoryInterface $category); 
    
    function moveUp(CatalogInterface $catalog, CategoryInterface $category);
    
    function moveDown(CatalogInterface $catalog, CategoryInterface $category);
}
