<?php

/*
* This file is part of the Sylius package.
*
* (c) Paweł Jędrzejewski
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Sylius\Bundle\CategorizerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Bundle\CategorizerBundle\Entity\Category as BaseCategory;

/**
 * Simple default implementation for nested categories.
 * Doctrine ORM driver implementation.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
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
