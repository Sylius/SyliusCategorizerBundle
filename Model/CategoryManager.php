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
 * Manages categories.
 * 
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
abstract class CategoryManager implements CategoryManagerInterface
{
    /**
     * Category class.
     * 
     * @var string
     */
    protected $class;
    
    /**
     * Constructor.
     * 
     * @var string $class
     */
    public function __construct($class)
    {
        $this->class = $class;
    }
    
    /**
     * Returns FQCN of category.
     * 
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }
}
