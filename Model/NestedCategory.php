<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CategorizerBundle\Model;

/**
 * Nested category model.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class NestedCategory extends Category implements NestedCategoryInterface
{
    /**
     * Parent category.
     *
     * @var NestedCategoryInterface
     */
    protected $parent;

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * {@inheritdoc}
     */
    public function setParent(NestedCategoryInterface $parent)
    {
        $this->parent = $parent;
    }
}
