<?php

namespace Sylius\Bundle\CatalogBundle\Entity;

use Sylius\Bundle\CatalogBundle\Entity\Category as BaseCategory;

class NestedCategory extends BaseCategory
{
    protected $treeLeft;
    protected $treeLevel;
    protected $treeRight;
    protected $treeRoot;
    protected $parent;
    protected $children;
    
    public function getParent()
    {
        return $this->parent;
    }
    
    public function setParent(NestedCategory $parent)
    {
        $this->parent = $parent;
    }
    
    public function getChildren()
    {
        return $this->children;
    }
    
    public function setChildren($children)
    {
        $this->children = $children;
    }
}