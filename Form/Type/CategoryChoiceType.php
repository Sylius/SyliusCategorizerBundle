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
use Sylius\Bundle\CatalogBundle\Provider\CatalogProvider;
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
     * Catalog provider.
     * 
     * @var CatalogProvider
     */
    protected $catalogProvider;
    
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
    public function __construct(CatalogProvider $catalogProvider, CategoryChoiceList $categoryChoiceList, CategoriesToArrayTransformer $categoriesToArrayTransformer, CategoryToIdTransformer $categoryToIdTransformer)
    {
        $this->catalogProvider = $catalogProvider;
        $this->categoryChoiceList = $categoryChoiceList;
        $this->categoriesToArrayTransformer = $categoriesToArrayTransformer;
        $this->categoryToIdTransformer = $categoryToIdTransformer;
    }
    
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        if (!isset($options['catalog_alias'])) {
            throw new \InvalidArgumentException('Catalog must be defined in category choice type options.');
        }
        
        $catalog = $this->catalogProvider->getCatalog($options['catalog_alias']);
        
        $this->categoryChoiceList->defineCatalog($catalog);
        
        if ($options['multiple']) {
            $this->categoriesToArrayTransformer->defineCatalog($catalog);
            $builder->prependClientTransformer($this->categoriesToArrayTransformer);
        } else {
            $this->categoryToIdTransformer->defineCatalog($catalog);
            $builder->prependClientTransformer($this->categoryToIdTransformer);
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        return array(
            'catalog_alias'	 => null,
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
