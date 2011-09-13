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

use Symfony\Component\Form\Extension\Core\ChoiceList\ArrayChoiceList;
use Sylius\Bundle\CatalogBundle\Model\CategoryManagerInterface;

/**
 * Category choice list.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class CategoryChoiceList extends ArrayChoiceList
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
     * @param $categoryManager
     */
    public function __construct(CategoryManagerInterface $categoryManager)
    {
        $this->categoryManager = $categoryManager;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getChoices()
    {
        foreach ($this->categoryManager->findCategories() as $category) {
            $this->choices[$category->getId()] = $category->getName();
        }
        
        return parent::getChoices();
    }
}
