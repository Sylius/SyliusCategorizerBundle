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

use RuntimeException;
use ReflectionClass;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sylius\Bundle\CatalogBundle\Model\CategoryInterface;
use Sylius\Bundle\CatalogBundle\Model\CategoryManager as BaseCategoryManager;
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
     * Entity repository.
     * 
     * @var EntityRepository
     */
    protected $repository;
    
    /**
     * Constructor.
     * 
     * @param Entity Manager $entityManager
     * @param string		 $class The category model class
     */
    public function __construct(EntityManager $entityManager, $class)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository($class);
        
        parent::__construct($class);
    }
    
    /**
     * {@inheritdoc}
     */
    public function createCategory()
    {
        $class = $this->getClass();
        return new $class;
    }
    
	/**
     * {@inheritdoc}
     */
    public function createPaginator(CategoryInterface $category)
    {
        $metadata = $this->entityManager->getClassMetadata($this->class);
        $itemAssociationMapping = $metadata->getAssociationMapping('items');
        
        $itemClass = $itemAssociationMapping['targetEntity'];
        $itemClassReflection = new ReflectionClass($itemClass);
        
        if ($itemClassReflection->implementsInterface('Sylius\\Bundle\\CatalogBundle\\Model\SingleCategoryItemInterface')) {
        
        $queryBuilder = $this->entityManager->createQueryBuilder()
            ->select('i')
            ->from($itemClass, 'i')
            ->where('i.category = ?1')
            ->setParameter(1, $category->getId());         
        } elseif ($itemClassReflection->implementsInterface('Sylius\\Bundle\\CatalogBundle\\Model\MultiCategoryItemInterface')) {
            $queryBuilder = $this->entityManager->createQueryBuilder()
            ->select('c')
            ->from($this->class, 'c')
            ->innerJoin('c.items', 'i')
            ->where('c.id = ?1')
            ->setParameter(1, $category->getId());  
        } else {
            throw new RuntimeException('The object associated with category as item must implement proper category item interface.');
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
    public function findCategory($id)
    {
        return $this->repository->find($id);
    }
    
    /**
     * {@inheritdoc}
     */
    public function findCategoryBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }
    
    /**
     * {@inheritdoc}
     */
    public function findCategories()
    {
        return $this->repository->findAll();
    }
    
    /**
     * {@inheritdoc}
     */
    public function findCategoriesBy(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }
}
