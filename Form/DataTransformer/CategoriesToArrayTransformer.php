<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CatalogBundle\Form\DataTransformer;

use Sylius\Bundle\CatalogBundle\Model\CatalogInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Sylius\Bundle\CatalogBundle\Model\CategoryInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Sylius\Bundle\CatalogBundle\Model\CategoryManagerInterface;

/**
 * Categories to array transformer.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class CategoriesToArrayTransformer implements DataTransformerInterface
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
     * @param CategoryManagerInterface $categoryManager
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
    public function transform($value)
    {
        if (null === $value) {
            return array();
        }
        
        if (0 === count($value)) {
            return array();
        }
        
        $categories = array();
        
        foreach ($value as $category) {
            $categories[] = $category->getId();
        }
        
        return $categories;
    }
    
    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        if (!is_array($value)) {
            throw new UnexpectedTypeException($value, 'array');
        }
        
        if (0 === count($value)) {
            return array();
        }
        
        if (null == $this->catalog) {
            throw new \RuntimeException('Catalog must be defined to transform categories into array.');
        }
        
        $categories = array();
        
        foreach($value as $categoryId) {
            $category = $this->categoryManager->findCategory($this->catalog, $categoryId);
            
            if (!$category) {
                throw new TransformationFailedException('Category with given id does not exist.');
            }
            
            $categories[] = $category;
        }
        
        return $categories;
    }
}
