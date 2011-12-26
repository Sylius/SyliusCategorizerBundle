<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CatalogBundle\Entity;

use Doctrine\ORM\Mapping\ClassMetadataInfo;

use Sylius\Bundle\CatalogBundle\Model\CatalogInterface;
use Sylius\Bundle\CatalogBundle\Model\CategoryInterface;
use Sylius\Bundle\CatalogBundle\Model\CategoryManager as BaseCategoryManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;

/**
 * ORM implementation of category model manager.
 * 
 * @author Paweł Jedrzejewski <pjedrzejewski@diweb.pl>
 */
class CategoryManager extends BaseCategoryManager
{
    /**
     * Entity manager.
     * 
     * @var EntityManager
     */
    protected $entityManager;
    
    /**
     * Repositories.
     * 
     * @var array
     */
    protected $repositories = array();
    
    /**
     * Constructor.
     * 
     * @param Entity Manager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * {@inheritdoc}
     */
    public function createCategory(CatalogInterface $catalog)
    {
        $class = $catalog->getOption('classes.model');
        return new $class;
    }
    
	/**
     * {@inheritdoc}
     */
    public function createPaginator(CatalogInterface $catalog, CategoryInterface $category, $sorter = null)
    {
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
                ->where($alias . '.category = ?1')
                ->setParameter(1, $category->getId());         
        } elseif (ClassMetadataInfo::MANY_TO_MANY === $itemAssociationMapping['type']) {
            $queryBuilder = $this->entityManager->createQueryBuilder()
                ->select($alias)
                ->from($itemClass, $alias)
                ->innerJoin($alias . '.categories', 'category')
                ->where('category.id = ?1')
                ->setParameter(1, $category->getId());  
        }
        
        if (null !== $sorter) {
            $sorter->sort($queryBuilder);
        }
            
        return new Pagerfanta(new DoctrineORMAdapter($queryBuilder->getQuery()));
    }    
    
    /**
     * {@inheritdoc}
     */
    public function persistCategory(CategoryInterface $category)
    {
        $this->entityManager->persist($category);
        $this->entityManager->flush();
    }
    
    /**
     * {@inheritdoc}
     */
    public function removeCategory(CategoryInterface $category)
    {
        $this->entityManager->remove($category);
        $this->entityManager->flush();
    }
    
    /**
     * {@inheritdoc}
     */
    public function findCategory(CatalogInterface $catalog, $id)
    {
        return $this->getRepository($catalog)->find($id);
    }
    
    /**
     * {@inheritdoc}
     */
    public function findCategoryBy(CatalogInterface $catalog, array $criteria)
    {
        return $this->getRepository($catalog)->findOneBy($criteria);
    }
    
    /**
     * {@inheritdoc}
     */
    public function findCategories(CatalogInterface $catalog, $asTree = false)
    {
        if ($asTree) {
            return $this->getRepository($catalog)->getRootNodes();
        }
        
        return $this->getRepository($catalog)->findAll();
    }
    
    /**
     * {@inheritdoc}
     */
    public function findCategoriesBy(CatalogInterface $catalog, array $criteria)
    {
        return $this->getRepository($catalog)->findBy($criteria);
    }
    
    public function moveCategoryUp(CatalogInterface $catalog, CategoryInterface $category)
    {
        if (!$catalog->getOption('nested')) {
            throw new \InvalidArgumentException('This catalog does not support nested categories.');
        }
        
        $repository = $this->getRepository($catalog);
        
        $repository->moveUp($category, 1);
        $this->entityManager->clear();
    }
    
    public function moveCategoryDown(CatalogInterface $catalog, CategoryInterface $category)
    {
        if (!$catalog->getOption('nested')) {
            throw new \InvalidArgumentException('This catalog does not support nested categories.');
        }
    
        $repository = $this->getRepository($catalog);
        
        $repository->moveDown($category, 1);
        
        $this->entityManager->clear();
    }
    
    /**
     * Returns repository for give class.
     * 
     * @param CatalogInterface $catalog
     */
    private function getRepository(CatalogInterface $catalog)
    {
        $categoryClass = $catalog->getOption('classes.model');
        
        if (!isset($this->repositories[$categoryClass])) {
            
            return $this->repositories[$categoryClass] = $this->entityManager->getRepository($categoryClass);
        }
        
        return $this->repositories[$categoryClass];
    }
}
