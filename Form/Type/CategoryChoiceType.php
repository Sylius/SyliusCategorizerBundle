<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CatalogBundle\Form\Type;

use Sylius\Bundle\CatalogBundle\Form\DataTransformer\CategoryToIdTransformer;
use Sylius\Bundle\CatalogBundle\Form\DataTransformer\CategoriesToArrayTransformer;
use Sylius\Bundle\CatalogBundle\Form\ChoiceList\CategoryChoiceList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

/**
 * Category choice form type.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class CategoryChoiceType extends AbstractType
{
    /**
     * Category choice list.
     * 
     * @var CategoryChoiceList
     */
    protected $categoryChoiceList;
    
    /**
     * Categories to array transformer.
     * 
     * @var CategoriesToArrayTransformer
     */
    protected $categoriesToArrayTransformer;
    
    /**
     * Category to id transformer.
     * 
     * @var CategoryToIdTransformer
     */
    protected $categoryToIdTransformer;
    
    /**
     * Constructor.
     * 
     * @param string $dataClass
     */
    public function __construct(CategoryChoiceList $categoryChoiceList, CategoriesToArrayTransformer $categoriesToArrayTransformer, CategoryToIdTransformer $categoryToIdTransformer)
    {
        $this->categoryChoiceList = $categoryChoiceList;
        $this->categoriesToArrayTransformer = $categoriesToArrayTransformer;
        $this->categoryToIdTransformer = $categoryToIdTransformer;
    }
    
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        if ($options['multiple']) {
            $builder
                ->prependClientTransformer($this->categoriesToArrayTransformer);
            ;
        } else {
            $builder->prependClientTransformer($this->categoryToIdTransformer);
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        return array(
            'multiple'		 => true,
            'expanded'		 => false,
            'choice_list'	 => $this->categoryChoiceList,
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function getParent(array $options)
    {
        return 'choice';
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sylius_catalog_category_choice';
    }
}
