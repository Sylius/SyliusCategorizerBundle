<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CategorizerBundle\Tests\Model;

/**
 * Category model test.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class CategoryTest extends \PHPUnit_Framework_TestCase
{
    public function testName()
    {
        $category = $this->getCategory();
        $this->assertNull($category->getName());

        $category->setName('testing category');
        $this->assertEquals('testing category', $category->getName());
    }

    public function testSlug()
    {
        $category = $this->getCategory();
        $this->assertNull($category->getSlug());

        $category->setSlug('testing-category');
        $this->assertEquals('testing-category', $category->getSlug());
    }

    public function testSetPosition()
    {
        $category = $this->getCategory();
        $category->setPosition(3);

        $this->assertEquals(3, $category->getPosition());
    }

    public function testIncrementPosition()
    {
        $category = $this->getCategory();
        $category->setPosition(3);
        $category->incrementPosition();

        $this->assertEquals(4, $category->getPosition());
    }

    public function testDecrementPosition()
    {
        $category = $this->getCategory();
        $category->setPosition(3);
        $category->decrementPosition();

        $this->assertEquals(2, $category->getPosition());
    }

    public function testCreatedAt()
    {
        $category = $this->getCategory();
        $createdAt = $category->getCreatedAt();
        sleep(1);

        $this->assertEquals($createdAt, $category->getCreatedAt());
    }

    public function testUpdatedAt()
    {
        $category = $this->getCategory();
        $updatedAt = $category->getUpdatedAt();
        sleep(1);

        $this->assertEquals($updatedAt, $category->getUpdatedAt());
    }

    public function testIncrementCreatedAt()
    {
        $category = $this->getCategory();
        $createdAt = $category->getCreatedAt();
        sleep(1);
        $category->incrementCreatedAt();

        $this->assertGreaterThan($createdAt, $category->getCreatedAt());
    }

    public function testIncrementUpdatedAt()
    {
        $category = $this->getCategory();
        $updatedAt = $category->getUpdatedAt();
        sleep(1);
        $category->incrementUpdatedAt();

        $this->assertGreaterThan($updatedAt, $category->getUpdatedAt());
    }

    public function testGetId()
    {
        $category = new EvilStubCategory();

        $this->assertEquals(666, $category->getId());
    }

    protected function getCategory()
    {
        return $this->getMockForAbstractClass('Sylius\Bundle\CategorizerBundle\Model\Category');
    }
}

class EvilStubCategory extends \Sylius\Bundle\CategorizerBundle\Model\Category
{
    public $id = 666;
}
