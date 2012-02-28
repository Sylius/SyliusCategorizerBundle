<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CategorizerBundle\Form\ChoiceList;

use Sylius\Bundle\CategorizerBundle\Model\CategoryInterface;
use Sylius\Bundle\CategorizerBundle\Model\CategoryManagerInterface;
use Sylius\Bundle\CategorizerBundle\Registry\CatalogInterface;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\ChoiceList\ObjectChoiceList;

/**
 * Category choice list.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class CategoryChoiceList extends ObjectChoiceList
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

        parent::__construct(array(), 'name', array(), null, null, 'id');
    }

    /**
     * Defines categories catalog.
     *
     * @param CatalogInterface $catalog
     */
    public function initializeCatalog(CatalogInterface $catalog)
    {
        $this->initialize($this->categoryManager->findCategories($catalog), array(), array());
    }
}
