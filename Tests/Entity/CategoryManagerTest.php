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

    private function getMockCategory()
    {
        return $this->getMock('Sylius\Bundle\CategorizerBundle\Model\CategoryInterface');
    }

    private function getMockEntityManager()
    {
        $entityManager = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->setMethods(array('persist', 'remove', 'flush', 'getRepository'))
            ->getMock()
        ;
        $entityManager->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue(null))
        ;

        return $entityManager;
    }

    private function getMockCatalogRegistry()
    {
        return $this->getMockBuilder('Sylius\Bundle\CategorizerBundle\Registry\CatalogRegistry')
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }
}
