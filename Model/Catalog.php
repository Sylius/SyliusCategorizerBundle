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
 * Default catalog implementation.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class Catalog implements CatalogInterface
{
    /**
     * Catalog alias.
     * 
     * @var string
     */
    protected $alias;
    
    /**
     * @var array
     */
    protected $options;
    
    protected $categoryManager;
    
    public function __construct($alias, array $options, CategoryManagerInterface $categoryManager)
    {
        $this->alias = $alias;
        $this->options = $options;
        $this->categoryManager = $categoryManager;
    }
    
    public function getAlias()
    {
        return $this->alias;
    }
    
    public function findCategory($id)
    {
        return $this->categoryManager->findCategory($this, $id);
    }
    
    public function findCategoryBy(array $criteria)
    {
        return $this->categoryManager->findCategoryBy($this, $criteria);
    }
    
    public function findCategories()
    {
        return $this->categoryManager->findCategories($this);
    }
    
    public function findCategoriesBy(array $criteria)
    {
        return $this->categoryManager->findCategoriesBy($this, $criteria);
    }
    
    public function getOptions()
    {
        return $this->options;
    }
    
    public function setOptions(array $options)
    {
        $this->options = $options;
    }
    
    public function getOption($key, $default = null)
    {
        if ($this->hasOption($key)) {
            
            return $this->options[$key];
        }
        
        if ($default !== null) {
            
            return $default;
        }
        
        throw new \InvalidArgumentException(sprintf('Requested option "%s" for catalog with alias "%s" does not exist.', $key, $this->getAlias()));
    }
    
    public function setOption($key, $value)
    {
        $this->options[$key] = $value;
    }
    
    public function hasOption($key)
    {
        return isset($this->options[$key]);
    }
}
