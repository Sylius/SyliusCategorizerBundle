<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CategorizerBundle\Manipulator;

use Sylius\Bundle\CategorizerBundle\Inflector\SlugizerInterface;
use Sylius\Bundle\CategorizerBundle\Model\CategoryInterface;
use Sylius\Bundle\CategorizerBundle\Model\CategoryManagerInterface;

/**
 * Category manipulator.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class CategoryManipulator implements CategoryManipulatorInterface
{
    /**
     * Category manager.
     *
     * @var CategoryManagerInterface
     */
    protected $categoryManager;

    /**
     * Constructor.
     *
     * @param CategoryManagerInterface     $categoryManager
     */
    public function __construct(CategoryManagerInterface $categoryManager)
    {
        $this->categoryManager = $categoryManager;
    }

    /**
     * {@inheritdoc}
     */
    public function create(CategoryInterface $category)
    {
        $this->categoryManager->persistCategory($category);
    }

    /**
     * {@inheritdoc}
     */
    public function update(CategoryInterface $category)
    {
        $this->categoryManager->persistCategory($category);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(CategoryInterface $category)
    {
        $this->categoryManager->removeCategory($category);
    }

    /**
     * {@inheritdoc}
     */
    public function moveUp(CategoryInterface $category)
    {
        $this->categoryManager->moveCategoryUp($category);
    }

    /**
     * {@inheritdoc}
     */
    public function moveDown(CategoryInterface $category)
    {
        $this->categoryManager->moveCategoryDown($category);
    }
}
