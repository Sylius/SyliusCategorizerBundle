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

use Symfony\Component\Form\Exception\TransformationFailedException;
use Sylius\Bundle\CatalogBundle\Model\CatalogInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Sylius\Bundle\CatalogBundle\Model\CategoryInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Sylius\Bundle\CatalogBundle\Model\CategoryManagerInterface;

/**
 * Category to id transformer.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class CategoryToIdTransformer implements DataTransformerInterface
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
        if (null == $value) {
            return null;
        }
        
        if (!$value instanceof CategoryInterface) {
            throw new UnexpectedTypeException($value, 'Sylius\Bundle\CatalogBundle\Model\CategoryInterface');
        }
        
        return $value->getId();
    }
    
    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        if ($value == null || $value == '') {
            return null;
        }
        
        if (!is_numeric($value)) {
            throw new UnexpectedTypeException($value, 'numeric');
        }
        
        if (null == $this->catalog) {
            throw new \RuntimeException('Catalog must be defined to transform id into category.');
        }
        
        $category = $this->categoryManager->findCategory($this->catalog, $value);
        
        if (!$category) {
            throw new TransformationFailedException('Category with given id does not exist.');
        }
        
        return $category;
    }
}
