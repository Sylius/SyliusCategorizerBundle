<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CatalogBundle\EventDispatcher\Event;

use Sylius\Bundle\CatalogBundle\Model\CategoryInterface;

use Symfony\Component\EventDispatcher\Event;

/**
 * Filter category event.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
final class FilterCategoryEvent extends Event
{
    /**
     * Category object.
     * 
     * @var CategoryInterface
     */
    private $category;
    
    /**
     * @param CategoryInterface $category
     */
    public function __construct(CategoryInterface $category)
    {
        $this->category = $category;
    }
    
    public function getCategory()
    {
        return $this->category;
    }  
}
