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

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\UnitOfWork;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Sylius\Bundle\CategorizerBundle\Model\CategoryInterface;
use Sylius\Bundle\CategorizerBundle\Model\CategoryManager as BaseCategoryManager;
use Sylius\Bundle\CategorizerBundle\Registry\CatalogInterface;
use Sylius\Bundle\CategorizerBundle\Registry\CatalogRegistry;

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
    protected $repositories;

    /**
     * Constructor.
     *
     * @param CataogRegistry $catalogRegistry
     * @param EntityManager  $entityManager
     */
    public function __construct(CatalogRegistry $catalogRegistry, EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repositories = array();

        parent::__construct($catalogRegistry);
    }

    /**
     * Creates new category instance based on given catalog.
     *
     * @param string|CatalogInterface $catalog
     *
     * @return CategoryInterface
     */
    public function createCategory($catalog)
    {
        $catalog = $this->catalogRegistry->guessCatalog($catalog);

        $class = $catalog->getOption('model');
        return new $class;
    }

    public function createPaginator(CategoryInterface $category, \Closure $callback = null)
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
                ->where($alias.'.category = ?1')
                ->setParameter(1, $category->getId());
        } elseif (ClassMetadataInfo::MANY_TO_MANY === $itemAssociationMapping['type']) {
            $queryBuilder = $this->entityManager->createQueryBuilder()
                ->select($alias)
                ->from($itemClass, $alias)
                ->innerJoin($alias.'.categories', 'category')
                ->where('category.id = ?1')
                ->setParameter(1, $category->getId());
        }

        if (null !== $callback) {
            $callback($queryBuilder);
        }

        return new Pagerfanta(new DoctrineORMAdapter($queryBuilder->getQuery()));
    }

    public function generateChoices($catalog)
    {
        return $this->getRepository($catalog)->createQueryBuilder('c')
            ->orderBy('c.position')
            ->getQuery()
            ->execute()
        ;
    }

    /**
     * Persists category.
     * Updates position if new category.
     *
     * @param CategoryInterface $category
     */
    public function persistCategory(CategoryInterface $category)
    {
        $this->entityManager->persist($category);
        $this->refreshCategoryPosition($category);
        $this->entityManager->flush();
    }

    /**
     * Removes category.
     * Updates positions in list.
     *
     * @param CategoryInterface $category
     */
    public function removeCategory(CategoryInterface $category)
    {
        if ($this->isNested($category)) {
            $this->getRepository($category)->removeFromTree($category);
            $this->entityManager->clear();
        } else {
            $this->entityManager->remove($category);
            $this->refreshCategoryPosition($category);
            $this->entityManager->flush();
        }
    }

    /**
     * Finds category by id.
     *
     * @param integer $id             $id      Category id
     * @param string|CatalogInterface $catalog The key to identify catalog or catalog object
     */
    public function findCategory($id, $catalog)
    {
        return $this->getRepository($catalog)->find($id);
    }

    /**
     * Finds category by criteria.
     *
     * @param array                   $criteria Search criteria
     * @param string|CatalogInterface $catalog  The key to identify catalog or catalog object
     */
    public function findCategoryBy(array $criteria, $catalog)
    {
        return $this->getRepository($catalog)->findOneBy($criteria);
    }

    /**
     * Finds all categories from catalog.
     * Returns nested array for nested categories with special `__children` key.
     *
     * @param string|CatalogInterface The key to identify catalog or catalog object
     */
    public function findCategories($catalog)
    {
        if ($this->isNested($catalog)) {
            return $this->getRepository($catalog)->childrenHierarchy();
        }

        return $this->getRepository($catalog)->createQueryBuilder('c')
            ->orderBy('c.position')
            ->getQuery()
            ->execute()
        ;
    }

    /**
     * Finds categories by criteria.
     *
     * @param array                   $criteria Search criteria
     * @param string|CatalogInterface $catalog  The key to identify catalog or catalog object
     */
    public function findCategoriesBy(array $criteria, $catalog)
    {
        return $this->getRepository($catalog)->findBy($criteria);
    }

    public function moveCategoryUp(CategoryInterface $category)
    {
        $repository = $this->getRepository($this->catalogRegistry->guessCatalog($category));
        if ($this->isNested($category)) {
            $repository->moveUp($category, 1);
            $this->entityManager->clear();
        } else {
            if (!$relatedCategory = $repository->findOneBy(array('position' => $category->getPosition() - 1))) {

                throw new \LogicException('Cannot move up top category.');
            }
            $this->swapCategoriesPosition($category, $relatedCategory);
        }
    }

    public function moveCategoryDown(CategoryInterface $category)
    {
        $repository = $this->getRepository($this->catalogRegistry->guessCatalog($category));
        if ($this->isNested($category)) {
            $repository->moveDown($category, 1);
            $this->entityManager->clear();
        } else {
            if (!$relatedCategory = $repository->findOneBy(array('position' => $category->getPosition() + 1))) {

                throw new \LogicException('Cannot move down bottom category.');
            }
            $this->swapCategoriesPosition($category, $relatedCategory);
        }
    }

    protected function swapCategoriesPosition(CategoryInterface $a, CategoryInterface $b)
    {
        $positionA = $a->getPosition();
        $positionB = $b->getPosition();

        $a->setPosition($positionB);
        $b->setPosition($positionA);

        $this->entityManager->persist($a);
        $this->entityManager->persist($b);
        $this->entityManager->flush();
    }

    /**
     * If category is not handled by Doctrine extensions
     * we handle positions manually.
     *
     * @param CategoryInterface $category
     */
    protected function refreshCategoryPosition(CategoryInterface $category)
    {
        if (!$this->isNested($category)) {
            if (UnitOfWork::STATE_REMOVED === $this->entityManager->getUnitOfWork()->getEntityState($category)) {
                $repository = $this->getRepository($this->catalogRegistry->guessCatalog($category));
                $repository->createQueryBuilder('c')
                    ->update()
                    ->set('c.position', 'c.position - 1')
                    ->where('c.position > :position')
                    ->setParameter('position', $category->getPosition())
                    ->getQuery()
                    ->execute()
                ;
            } elseif (0 === $category->getPosition()) {
                $maxPosition = $this->getMaxPosition($category);
                $category->setPosition($maxPosition + 1);
            }
        }
    }

    /**
     * Returns max position for specific catalog.
     *
     * @param CategoryInterface $category
     */
    protected function getMaxPosition(CategoryInterface $category)
    {
        $repository = $this->getRepository($this->catalogRegistry->guessCatalog($category));

        return $repository->createQueryBuilder('c')
            ->select('MAX(c.position)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * Checks whether the category is nested or not.
     *
     * @param string|CategoryInterface|CatalogInterface $guessable
     */
    protected function isNested($guessable)
    {
        $catalog = $this->catalogRegistry->guessCatalog($guessable);
        $class = $catalog->getOption('model');
        $reflection = new \ReflectionClass($class);

        return $reflection->isSubclassOf('Sylius\Bundle\CategorizerBundle\Entity\NestedCategory');
    }

    /**
     * Returns repository for given catalog.
     *
     * @param string|CategoryInterface|CatalogInterface $guessable
     */
    protected function getRepository($guessable)
    {
        $catalog = $this->catalogRegistry->guessCatalog($guessable);

        $categoryClass = $catalog->getOption('model');

        if (!isset($this->repositories[$categoryClass])) {
            $this->repositories[$categoryClass] = $this->entityManager->getRepository($categoryClass);
        }

        return $this->repositories[$categoryClass];
    }
}
