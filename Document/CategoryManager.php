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

use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Doctrine\ODM\MongoDB\UnitOfWork;
use Sylius\Bundle\CategorizerBundle\Model\CategoryInterface;
use Sylius\Bundle\CategorizerBundle\Model\CategoryManager as BaseCategoryManager;
use Sylius\Bundle\CategorizerBundle\Registry\CatalogInterface;
use Sylius\Bundle\CategorizerBundle\Registry\CatalogRegistry;

/**
 * MongoDB ODM implementation of category model manager.
 *
 * @author Paweł Jedrzejewski <pjedrzejewski@diweb.pl>
 */
class CategoryManager extends BaseCategoryManager
{
    /**
     * Document manager.
     *
     * @var DocumentManager
     */
    protected $documentManager;

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
     * @param DocumentManager  $documentManager
     */
    public function __construct(CatalogRegistry $catalogRegistry, DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
        $this->repositories = array();

        parent::__construct($catalogRegistry);
    }

    /**
     * {@inheritdoc}
     */
    public function createCategory($catalog)
    {
        $catalog = $this->catalogRegistry->guessCatalog($catalog);

        $class = $catalog->getOption('model');
        return new $class;
    }

    /**
     * {@inheritdoc}
     */
    public function generateChoices($catalog)
    {
        $queryBuilder = $this->getRepository($catalog)->createQueryBuilder();

        if ($this->isNested($catalog)) {
            $queryBuilder->sort('position');
        } else {
            $queryBuilder->sort('position');
        }

        return $queryBuilder->getQuery()->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function persistCategory(CategoryInterface $category)
    {
        $this->documentManager->persist($category);
        $this->refreshCategoryPosition($category);
        $this->documentManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function removeCategory(CategoryInterface $category)
    {
        if ($this->isNested($category)) {
            $this->getRepository($category)->removeFromTree($category);
            $this->documentManager->clear();
        } else {
            $this->documentManager->remove($category);
            $this->documentManager->flush();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function findCategory($id, $catalog)
    {
        return $this->getRepository($catalog)->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function findCategoryBy(array $criteria, $catalog)
    {
        return $this->getRepository($catalog)->findOneBy($criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function findCategories($catalog)
    {
        if ($this->isNested($catalog)) {
            return $this->getRepository($catalog)->childrenHierarchy();
        }

        return $this->getRepository($catalog)->createQueryBuilder()
            ->sort('position')
            ->getQuery()
            ->execute()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function findCategoriesBy(array $criteria, $catalog)
    {
        return $this->getRepository($catalog)->findBy($criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function moveCategoryUp(CategoryInterface $category)
    {
        $repository = $this->getRepository($this->catalogRegistry->guessCatalog($category));
        if ($this->isNested($category)) {
            $repository->moveUp($category, 1);
            $this->documentManager->clear();
        } else {
        }
    }

    /**
     * {@inheritdoc}
     */
    public function moveCategoryDown(CategoryInterface $category)
    {
        $repository = $this->getRepository($this->catalogRegistry->guessCatalog($category));
        if ($this->isNested($category)) {
            $repository->moveDown($category, 1);
            $this->documentManager->clear();
        } else {
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function isNested($guessable)
    {
        $catalog = $this->catalogRegistry->guessCatalog($guessable);
        $class = $catalog->getOption('model');
        $reflection = new \ReflectionClass($class);

        return $reflection->isSubclassOf('Sylius\Bundle\CategorizerBundle\Model\NestedCategoryInterface');
    }

    /**
     * {@inheritdoc}
     */
    protected function getRepository($guessable)
    {
        $catalog = $this->catalogRegistry->guessCatalog($guessable);

        $categoryClass = $catalog->getOption('model');

        if (!isset($this->repositories[$categoryClass])) {
            $this->repositories[$categoryClass] = $this->documentManager->getRepository($categoryClass);
        }

        return $this->repositories[$categoryClass];
    }
}
