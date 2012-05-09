<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CategorizerBundle\Document;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;
use Pagerfanta\Adapter\DoctrineODMMongoDBAdapter;
use Pagerfanta\Pagerfanta;
use Sylius\Bundle\CategorizerBundle\Model\CategoryInterface;
use Sylius\Bundle\CategorizerBundle\Model\CategoryLoader as BaseCategoryLoader;
use Sylius\Bundle\CategorizerBundle\Registry\CatalogRegistry;

/**
 * Doctrine MongoDB ODM driver implementation of category model loader.
 *
 * @author Paweł Jedrzejewski <pjedrzejewski@diweb.pl>
 */
class CategoryLoader extends BaseCategoryLoader
{
    /**
     * Document manager.
     *
     * @var DocumentManager
     */
    protected $documentManager;

    /**
     * Constructor.
     *
     * @param CataogRegistry $catalogRegistry
     * @param DocumentManager  $documentManager
     */
    public function __construct(CatalogRegistry $catalogRegistry, DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;

        parent::__construct($catalogRegistry);
    }

    /**
     * {@inheritdoc}
     */
    public function loadCategory(CategoryInterface $category)
    {
        $catalog = $this->catalogRegistry->guessCatalog($category);
        $categoryClass = get_class($category);
        $property = $catalog->getOption('property');

        $metadata = $this->documentManager->getClassMetadata($categoryClass);
        $itemAssociationMapping = $metadata->getAssociationMapping($property);

        $itemClass = $itemAssociationMapping['targetDocument'];
        $itemClassReflection = new \ReflectionClass($itemClass);

        $alias = $property[0];

        return new Pagerfanta(new DoctrineODMMongoDBAdapter($queryBuilder->getQuery()));
    }
}
