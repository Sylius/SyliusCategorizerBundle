<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CategorizerBundle\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Sylius\Component\Categorizer\Model\CategoryInterface;
use Sylius\Component\Categorizer\Loader\CategoryLoader as BaseCategoryLoader;
use Sylius\Component\Categorizer\Registry\CatalogRegistry;

/**
 * Doctrine ORM driver implementation of category model loader.
 *
 * @author Paweł Jedrzejewski <pjedrzejewski@diweb.pl>
 */
class CategoryLoader extends BaseCategoryLoader
{
    /**
     * Entity manager.
     *
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * Constructor.
     *
     * @param CataogRegistry $catalogRegistry
     * @param EntityManager  $entityManager
     */
    public function __construct(CatalogRegistry $catalogRegistry, EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;

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

        $metadata = $this->entityManager->getClassMetadata($categoryClass);
        $itemAssociationMapping = $metadata->getAssociationMapping($property);

        $itemClass = $itemAssociationMapping['targetEntity'];
        $itemClassReflection = new \ReflectionClass($itemClass);

        $alias = $property[0];

        if (ClassMetadataInfo::ONE_TO_MANY === $itemAssociationMapping['type']) {
            $queryBuilder = $this->entityManager->createQueryBuilder()
                ->select($alias)
                ->from($itemClass, $alias)
                ->where($alias.'.'.$itemAssociationMapping['mappedBy'].' = ?1')
                ->setParameter(1, $category->getId())
            ;
        } elseif (ClassMetadataInfo::MANY_TO_MANY === $itemAssociationMapping['type']) {
            $queryBuilder = $this->entityManager->createQueryBuilder()
                ->select($alias)
                ->from($itemClass, $alias)
                ->innerJoin($alias.'.'.$itemAssociationMapping['mappedBy'], 'category')
                ->where('category.id = ?1')
                ->setParameter(1, $category->getId())
            ;
        }

        return new Pagerfanta(new DoctrineORMAdapter($queryBuilder->getQuery()));
    }
}
