<?php

/*
* This file is part of the Sylius package.
*
* (c) Paweł Jędrzejewski
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Sylius\Bundle\CategorizerBundle\Document;

use Sylius\Bundle\CategorizerBundle\Model\NestedCategory as BaseNestedCategory;

/**
 * Simple default implementation for nested categories.
 * Doctrine MongoDB ODM driver implementation.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class NestedCategory extends BaseNestedCategory
{
    /**
     * Tree path.
     *
     * @var string
     */
    protected $path;

    /**
     * Tree level.
     * Required by DoctrineExtensions.
     *
     * @var mixed
     */
    protected $level;

    /**
     * Get path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set path.
     *
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Get category level.
     *
     * @return integer
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set level.
     *
     * @param integer $level
     */
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
