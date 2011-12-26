<?php

namespace Sylius\Bundle\CatalogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Bundle\CatalogBundle\Entity\Category as BaseCategory;

class NestedCategory extends BaseCategory
{
    public $treeLeft;
    public $treeLevel;
    public $treeRight;
    public $treeRoot;
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
    
    public function setChildren(ArrayCollection $children)
    {
        $this->children = $children;
    }
    
    public function hasChildren()
    {
        return !$this->children->isEmpty();
    }
}