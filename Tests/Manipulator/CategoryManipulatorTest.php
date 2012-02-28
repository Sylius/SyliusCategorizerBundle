<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CategorizerBundle\Tests\Manipulator;

use Sylius\Bundle\CategorizerBundle\Manipulator\CategoryManipulator;

/**
 * Category manipulator test.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class CategoryManipulatorTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateSetsCategorySlug()
    {
        $slugizer = $this->getMockSlugizer();
        $slugizer->expects($this->once())
            ->method('slugize')
            ->with($this->equalTo('foo bar'))
            ->will($this->returnValue('foo-bar'))
        ;

        $category = $this->getMockCategory();
        $category->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('foo bar'))
        ;
        $category->expects($this->once())
            ->method('setSlug')
            ->with($this->equalTo('foo-bar'))
        ;

        $manipulator = new CategoryManipulator($this->getMockCategoryManager(), $slugizer);
        $manipulator->create($category);
    }

    public function testCreateIncrementsCategoryCreatedAt()
    {
        $category = $this->getMockCategory();
        $category->expects($this->once())
            ->method('incrementCreatedAt')
        ;

        $manipulator = new CategoryManipulator($this->getMockCategoryManager(), $this->getMockSlugizer());
        $manipulator->create($category);
    }

    public function testCreatePersistsCategory()
    {
        $category = $this->getMockCategory();

        $categoryManager = $this->getMockCategoryManager();
        $categoryManager->expects($this->once())
            ->method('persistCategory')
            ->with($this->equalTo($category))
        ;

        $manipulator = new CategoryManipulator($categoryManager, $this->getMockSlugizer());
        $manipulator->create($category);
    }

    public function testUpdateSetsCategorySlug()
    {
        $slugizer = $this->getMockSlugizer();
        $slugizer->expects($this->once())
            ->method('slugize')
            ->with($this->equalTo('foo bar'))
            ->will($this->returnValue('foo-bar'))
        ;

        $category = $this->getMockCategory();
        $category->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('foo bar'))
        ;
        $category->expects($this->once())
            ->method('setSlug')
            ->with($this->equalTo('foo-bar'))
        ;

        $manipulator = new CategoryManipulator($this->getMockCategoryManager(), $slugizer);
        $manipulator->update($category);
    }

    public function testUpdateIncrementsCategoryUpdatedAt()
    {
        $category = $this->getMockCategory();
        $category->expects($this->once())
            ->method('incrementUpdatedAt')
        ;

        $manipulator = new CategoryManipulator($this->getMockCategoryManager(), $this->getMockSlugizer());
        $manipulator->update($category);
    }

    public function testUpdatePersistsCategory()
    {
        $category = $this->getMockCategory();

        $categoryManager = $this->getMockCategoryManager();
        $categoryManager->expects($this->once())
            ->method('persistCategory')
            ->with($this->equalTo($category))
        ;

        $manipulator = new CategoryManipulator($categoryManager, $this->getMockSlugizer());
        $manipulator->update($category);
    }

    public function testDeleteRemovesCategory()
    {
        $category = $this->getMockCategory();

        $categoryManager = $this->getMockCategoryManager();
        $categoryManager->expects($this->once())
            ->method('removeCategory')
            ->with($this->equalTo($category))
        ;

        $manipulator = new CategoryManipulator($categoryManager, $slugizer = $this->getMockSlugizer());
        $manipulator->delete($category);
    }

    private function getMockCategory()
    {
        return $this->getMock('Sylius\Bundle\CategorizerBundle\Model\CategoryInterface');
    }

    private function getMockCategoryManager()
    {
        $categoryManager = $this->getMockBuilder('Sylius\Bundle\CategorizerBundle\Model\CategoryManagerInterface')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $categoryManager->expects($this->any())
            ->method('persistCategory')
            ->will($this->returnValue(null))
        ;

        return $categoryManager;
    }

    private function getMockSlugizer()
    {
        return $this->getMock('Sylius\Bundle\CategorizerBundle\Inflector\SlugizerInterface');
    }
}
