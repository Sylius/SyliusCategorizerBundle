<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CatalogBundle\Form\ChoiceList;

use Sylius\Bundle\CatalogBundle\Model\CategoryInterface;

use Sylius\Bundle\CatalogBundle\Model\CatalogInterface;
use Sylius\Bundle\CatalogBundle\Model\CategoryManagerInterface;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;

/**
 * Category choice list.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class CategoryChoiceList extends ChoiceList
{
    /**
     * Category manager.
     *
     * @var CategoryManagerInterface
     */
    protected $categoryManager;

    /**
     * Catalog.
     *
     * @var CatalogInterface
     */
    protected $catalog;

    /**
     * Constructor.
     *
     * @param $categoryManager
     */
    public function __construct(CategoryManagerInterface $categoryManager)
    {
        $this->categoryManager = $categoryManager;
    }

    /**
     * Defines from which catalog load categories.
     *
     * @param CatalogInterface $catalog
     */
    public function defineCatalog(CatalogInterface $catalog)
    {
        $this->catalog = $catalog;
    }

    /**
     * {@inheritdoc}
     */
    public function getChoices()
    {
        if (null == $this->catalog) {
            throw new \RuntimeException('Catalog must be defined to load categories.');
        }

        $this->choices = array();

        if ($this->catalog->getOption('nested')) {
            foreach ($this->categoryManager->findCategories($this->catalog, true) as $category) {
                $this->choices[$category->getId()] = $category->getName();
                if ($category->hasChildren()) {
                    $this->generateNestedChoices($category);
                }
            }
        } else {
            foreach ($this->categoryManager->findCategories($this->catalog) as $category) {
                $this->choices[$category->getId()] = $category->getName();
            }
        }

        return parent::getChoices();
    }

    private function generateNestedChoices(CategoryInterface $category)
    {
        if ($category->hasChildren()) {
            foreach ($category->getChildren() as $category) {
                $this->choices[$category->getId()] = str_repeat("—", $category->treeLevel * 2) . ' - ' . $category->getName();
                $this->generateNestedChoices($category);
            }
        }
    }
}
