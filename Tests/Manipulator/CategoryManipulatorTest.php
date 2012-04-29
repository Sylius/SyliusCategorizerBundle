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
    public function testCreatePersistsCategory()
    {
        $category = $this->getMockCategory();

        $categoryManager = $this->getMockCategoryManager();
        $categoryManager->expects($this->once())
            ->method('persistCategory')
            ->with($this->equalTo($category))
        ;

        $manipulator = new CategoryManipulator($categoryManager);
        $manipulator->create($category);
    }

    public function testUpdatePersistsCategory()
    {
        $category = $this->getMockCategory();

        $categoryManager = $this->getMockCategoryManager();
        $categoryManager->expects($this->once())
            ->method('persistCategory')
            ->with($this->equalTo($category))
        ;

        $manipulator = new CategoryManipulator($categoryManager);
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

        $manipulator = new CategoryManipulator($categoryManager);
        $manipulator->delete($category);
    }

    public function testMoveUp()
    {
        $category = $this->getMockCategory();

        $categoryManager = $this->getMockCategoryManager();
        $categoryManager->expects($this->once())
            ->method('moveCategoryUp')
            ->with($this->equalTo($category));

        $manipulator = new CategoryManipulator($categoryManager);
        $manipulator->moveUp($category);
    }

    public function testMoveDown()
    {
        $category = $this->getMockCategory();

        $categoryManager = $this->getMockCategoryManager();
        $categoryManager->expects($this->once())
            ->method('moveCategoryDown')
            ->with($this->equalTo($category));

        $manipulator = new CategoryManipulator($categoryManager);
        $manipulator->moveDown($category);
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

}
