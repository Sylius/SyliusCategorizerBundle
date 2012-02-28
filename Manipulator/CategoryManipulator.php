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
     * Slugizer inflector.
     *
     * @var SlugizerInterface
     */
    protected $slugizer;

    /**
     * Constructor.
     *
     * @param CategoryManagerInterface     $categoryManager
     * @param SlugizerInterface         $slugizer
     */
    public function __construct(CategoryManagerInterface $categoryManager, SlugizerInterface $slugizer)
    {
        $this->categoryManager = $categoryManager;
        $this->slugizer = $slugizer;
    }

    public function create(CategoryInterface $category)
    {
        $category->setSlug($this->slugizer->slugize($category->getName()));
        $category->incrementCreatedAt();

        $this->categoryManager->persistCategory($category);
    }

    public function update(CategoryInterface $category)
    {
        $category->setSlug($this->slugizer->slugize($category->getName()));
        $category->incrementUpdatedAt();

        $this->categoryManager->persistCategory($category);
    }

    public function delete(CategoryInterface $category)
    {
        $this->categoryManager->removeCategory($category);
    }

    public function moveUp(CategoryInterface $category)
    {
        $this->categoryManager->moveCategoryUp($category);
    }

    public function moveDown(CategoryInterface $category)
    {
        $this->categoryManager->moveCategoryDown($category);
    }
}
