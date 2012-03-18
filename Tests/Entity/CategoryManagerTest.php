<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CategorizerBundle\Tests\Entity;

use Sylius\Bundle\CategorizerBundle\Entity\CategoryManager;

/**
 * Category manager test for doctrine/orm driver.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 * @author Leszek Prabucki <leszek.prabucki@gmail.pl>
 */
class CategoryManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testPersistCategory()
    {
        $category = $this->getMockCategory();

        $entityManager = $this->getMockEntityManager();
        $entityManager->expects($this->once())
            ->method('persist')
            ->with($this->equalTo($category))
        ;
        $entityManager->expects($this->once())
            ->method('flush')
        ;

        $categoryManager = $this->getMockBuilder('Sylius\Bundle\CategorizerBundle\Entity\CategoryManager')
            ->setMethods(array('refreshCategoryPosition'))
            ->setConstructorArgs(array($this->getMockCatalogRegistry(), $entityManager))
            ->getMock()
        ;

        $categoryManager->persistCategory($category);
    }

    public function testRemoveCategory()
    {
        $category = $this->getMockCategory();

        $entityManager = $this->getMockEntityManager();
        $entityManager->expects($this->once())
            ->method('remove')
            ->with($this->equalTo($category))
        ;
        $entityManager->expects($this->once())
            ->method('flush')
        ;

        $categoryManager = $this->getMockBuilder('Sylius\Bundle\CategorizerBundle\Entity\CategoryManager')
            ->setMethods(array('refreshCategoryPosition'))
            ->setConstructorArgs(array($this->getMockCatalogRegistry(), $entityManager))
            ->getMock()
        ;

        $categoryManager->removeCategory($category);
    }

    public function testCreatePaginatorForOneToManyType()
    {
        $category = $this->getMockCategory();
        $catalogRegistry = $this->getMockCatalogRegistryForCreatePaginator();
        $entityManager = $this->getMockEntityManagerForCreatePaginator(\Doctrine\ORM\Mapping\ClassMetadataInfo::ONE_TO_MANY);

        $categoryManager = new CategoryManager($catalogRegistry, $entityManager);
        $paginator = $categoryManager->createPaginator($category, function ($queryBuilder) {});

        $this->assertInstanceOf('Pagerfanta\Pagerfanta', $paginator);
    }

    public function testCreatePaginatorForManyToManyType()
    {
        $category = $this->getMockCategory();
        $catalogRegistry = $this->getMockCatalogRegistryForCreatePaginator();
        $entityManager = $this->getMockEntityManagerForCreatePaginator(\Doctrine\ORM\Mapping\ClassMetadataInfo::MANY_TO_MANY);

        $categoryManager = new CategoryManager($catalogRegistry, $entityManager);
        $paginator = $categoryManager->createPaginator($category, function ($queryBuilder) {});

        $this->assertInstanceOf('Pagerfanta\Pagerfanta', $paginator);
    }

    public function testCreateCategory()
    {
        $catalog = $this->getMockCatalog();

        $catalogRegistry = $this->getMockCatalogRegistry();
        $catalogRegistry->expects($this->once())
            ->method('guessCatalog')
            ->with($this->equalTo('test'))
            ->will($this->returnValue($catalog))
        ;

        $categoryManager = new CategoryManager($catalogRegistry, $this->getMockEntityManager());
        $object = $categoryManager->createCategory('test');

        $this->assertInstanceOf('Sylius\Bundle\CategorizerBundle\Tests\Entity\ModelClass', $object);
    }

    public function testGenerateChoices()
    {
        $catalog = $this->getMockCatalog();

        $catalogRegistry = $this->getMockCatalogRegistry();
        $catalogRegistry->expects($this->once())
            ->method('guessCatalog')
            ->with($this->equalTo('test'))
            ->will($this->returnValue($catalog))
        ;

        $choices = array('choice1', 'choice2');
        $entityManager = $this->getMockEntityManager($choices);

        $categoryManager = new CategoryManager($catalogRegistry, $entityManager);
        $this->assertEquals(array('choice1', 'choice2'), $categoryManager->generateChoices('test'));
    }

    public function testFindCategory()
    {
        $catalog = $this->getMockCatalog();

        $catalogRegistry = $this->getMockCatalogRegistry();
        $catalogRegistry->expects($this->once())
            ->method('guessCatalog')
            ->with($this->equalTo('test'))
            ->will($this->returnValue($catalog))
        ;

        $findResult = array('test' =>  array('id' => 666));
        $findIdParam = 666;

        $entityManager = $this->getMockEntityManager($findResult, $findIdParam);

        $categoryManager = new CategoryManager($catalogRegistry, $entityManager);
        $this->assertEquals($findResult, $categoryManager->findCategory(666, 'test'));
    }

    public function testFindCategoryBy()
    {
        $catalog = $this->getMockCatalog();

        $catalogRegistry = $this->getMockCatalogRegistry();
        $catalogRegistry->expects($this->once())
            ->method('guessCatalog')
            ->with($this->equalTo('test'))
            ->will($this->returnValue($catalog))
        ;

        $findResult = array('test' =>  array('id' => 666));
        $findParams = array('id' => 666);

        $entityManager = $this->getMockEntityManager($findResult, $findParams);

        $categoryManager = new CategoryManager($catalogRegistry, $entityManager);
        $this->assertEquals($findResult, $categoryManager->findCategoryBy(array('id' => 666), 'test'));
    }

    public function testFindCategoriesBy()
    {
        $catalog = $this->getMockCatalog();

        $catalogRegistry = $this->getMockCatalogRegistry();
        $catalogRegistry->expects($this->once())
            ->method('guessCatalog')
            ->with($this->equalTo('test'))
            ->will($this->returnValue($catalog))
        ;

        $findResult = array('test' =>  array('id' => 666), 'test2' => array('id' => 777));
        $findParams = array('published' => true);

        $entityManager = $this->getMockEntityManager($findResult, $findParams);

        $categoryManager = new CategoryManager($catalogRegistry, $entityManager);
        $this->assertEquals($findResult, $categoryManager->findCategoriesBy($findParams, 'test'));
    }

    public function testFindCategoriesWhenNotNested()
    {
        $catalog = $this->getMockCatalog();

        $catalogRegistry = $this->getMockCatalogRegistry();
        $catalogRegistry->expects($this->exactly(2))
            ->method('guessCatalog')
            ->with($this->equalTo('test'))
            ->will($this->returnValue($catalog))
        ;

        $findResult = array('test' =>  array('id' => 666), 'test2' => array('id' => 777));

        $entityManager = $this->getMockEntityManager($findResult);

        $categoryManager = new CategoryManager($catalogRegistry, $entityManager);
        $this->assertEquals($findResult, $categoryManager->findCategories('test'));
    }

    public function testFindCategoriesWhenNested()
    {
        $catalog = $this->getMockCatalog('Sylius\Bundle\CategorizerBundle\Tests\Entity\NestedModelClass');

        $catalogRegistry = $this->getMockCatalogRegistry();
        $catalogRegistry->expects($this->any())
            ->method('guessCatalog')
            ->with($this->equalTo('test'))
            ->will($this->returnValue($catalog))
        ;

        $findResult = array('test' =>  array('id' => 666), 'test2' => array('id' => 777));

        $entityManager = $this->getMockEntityManager($findResult);

        $categoryManager = new CategoryManager($catalogRegistry, $entityManager);
        $this->assertEquals($findResult, $categoryManager->findCategories('test'));
    }

    private function getMockCatalog($modelClass = 'Sylius\Bundle\CategorizerBundle\Tests\Entity\ModelClass')
    {
        $catalog = $this->getMock('Sylius\Bundle\CategorizerBundle\Registry\CatalogInterface');
        $catalog->expects($this->any())
            ->method('getOption')
            ->with($this->equalTo('model'))
            ->will($this->returnValue($modelClass));
        ;

        return $catalog;
    }

    private function getMockCategory()
    {
        return $this->getMock('Sylius\Bundle\CategorizerBundle\Model\CategoryInterface');
    }

    private function getMockEntityManager($repoResult = array(), $findParams = array())
    {
        $entityManager = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->setMethods(array('persist', 'remove', 'flush', 'getRepository', 'getClassMetadata'))
            ->getMock()
        ;

        $entityManager->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($this->getMockRepository($repoResult, $findParams)));
        ;

        return $entityManager;
    }

    private function getMockRepository($collection = array(), $findParams = array())
    {
        $query = $this->getMockForAbstractClass('Doctrine\ORM\AbstractQuery', array(), '', false, false, true, array('execute'));

        $query
            ->expects($this->any())
            ->method('execute')
            ->will($this->returnValue($collection))
        ;

        $queryBuilder = $this->getMockBuilder('Doctrine\ORM\QueryBuilder')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $queryBuilder->expects($this->any())
            ->method('orderBy')
            ->will($this->returnSelf())
        ;

        $queryBuilder->expects($this->any())
            ->method('getQuery')
            ->will($this->returnValue($query))
        ;

        $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->setMethods(array('createQueryBuilder', 'findById', 'find', 'findBy', 'findOneBy', 'childrenHierarchy'))
            ->getMock();
        ;

        $repository->expects($this->any())
            ->method('createQueryBuilder')
            ->will($this->returnValue($queryBuilder))
        ;

        $repository->expects($this->any())
            ->method('findById')
            ->with($this->equalTo($findParams))
            ->will($this->returnValue($collection))
        ;

        $repository->expects($this->any())
            ->method('findOneBy')
            ->with($this->equalTo($findParams))
            ->will($this->returnValue($collection))
        ;

        $repository->expects($this->any())
            ->method('findBy')
            ->with($this->equalTo($findParams))
            ->will($this->returnValue($collection))
        ;

        $repository->expects($this->any())
            ->method('find')
            ->with($this->equalTo($findParams))
            ->will($this->returnValue($collection))
        ;

        $repository->expects($this->any())
            ->method('childrenHierarchy')
            ->will($this->returnValue($collection))
        ;

        return $repository;
    }

    private function getMockCatalogRegistry()
    {
        return $this->getMockBuilder('Sylius\Bundle\CategorizerBundle\Registry\CatalogRegistry')
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    private function getMockCatalogRegistryForCreatePaginator()
    {
        $catalog = $this->getMock('Sylius\Bundle\CategorizerBundle\Registry\CatalogInterface');
        $catalog->expects($this->any())
            ->method('getOption')
            ->will($this->returnValue(array('some property')))
        ;

        $catalogRegistry = $this->getMockCatalogRegistry();
        $catalogRegistry->expects($this->once())
            ->method('guessCatalog')
            ->will($this->returnValue($catalog))
        ;

        return $catalogRegistry;
    }

    private function getMockEntityManagerForCreatePaginator($type)
    {
        $classMetadata = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadataInfo')
            ->disableOriginalConstructor()
            ->getMock();
        $classMetadata->expects($this->once())
            ->method('getAssociationMapping')
            ->will($this->returnValue(array(
                'type' => $type,
                'targetEntity' => 'Sylius\Bundle\CategorizerBundle\Entity\Category'
            )))
        ;

        $entityManager = $this->getMockEntityManager();
        $entityManager->expects($this->once())
            ->method('getClassMetadata')
            ->will($this->returnValue($classMetadata))
        ;

        return $entityManager;
    }
}

class ModelClass
{}

class NestedModelClass extends \Sylius\Bundle\CategorizerBundle\Entity\NestedCategory
{}
