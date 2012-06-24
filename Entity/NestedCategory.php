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
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Categorizer\Model\NestedCategory as BaseNestedCategory;

/**
 * Simple default implementation for nested categories.
 * Doctrine ORM driver implementation.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class NestedCategory extends BaseNestedCategory
{

    /**
     * Tree root.
     * Required by DoctrineExtensions.
     *
     * @var mixed
     */
    protected $root;

    /**
     * Required by DoctrineExtensions.
     *
     * @var mixed
     */
    protected $left;

    /**
     * Required by DoctrineExtensions.
     *
     * @var mixed
     */
    protected $right;
    /**
     * Tree level.
     * Required by DoctrineExtensions.
     *
     * @var mixed
     */
    protected $level;

    public function getRoot()
    {
        return $this->root;
    }

    public function setRoot($root)
    {
        $this->root = $root;
    }

    public function getLeft()
    {
        return $this->left;
    }

    public function setLeft($left)
    {
        $this->left = $left;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return str_repeat('---', $this->level).' '.$this->name;
    }
}
