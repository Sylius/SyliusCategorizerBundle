<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CatalogBundle\Form\Factory;

use Sylius\Bundle\CatalogBundle\Model\CatalogInterface;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Creates a categor form type for given catalog.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class CategoryFormTypeFactory
{
    /**
     * @var FormFactoryInterface
     */
    protected $factory;
    
    public function __construct(FormFactoryInterface $factory)
    {
        $this->factory = $factory;
    }
    
    /**
     * @param CatalogInterface $catalog
     * @param array 		   $options
     */
    public function create(CatalogInterface $catalog, array $options = array())
    {
        $categoryFormTypeClass = $catalog->getOption('classes.form');
        $categoryFormType = new $categoryFormTypeClass($catalog->getOption('classes.model'));
        
        return $this->factory->create($categoryFormType, $options);
    }
}