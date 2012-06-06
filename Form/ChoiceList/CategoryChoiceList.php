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
 * @author Саша Стаменковић <umpirsky@gmail.com>
 */
class CategoryChoiceList extends ObjectChoiceList
{
    /**
     * Constructor.
     *
     * @param CategoryManagerInterface $categoryManager
     * @param string|CatalogInterface  $catalog
     */
    public function __construct(CategoryManagerInterface $categoryManager, $catalog)
    {
        parent::__construct(array(), 'label', array(), null, null, 'id');
        
        $this->initialize($categoryManager->generateChoices($catalog), array(), array());
    }
}
