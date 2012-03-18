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

use Sylius\Bundle\CategorizerBundle\Entity\NestedCategory;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Nested category test
 *
 * @author Leszek Prabucki <leszek.prabucki@gmail.com>
 */
class NestedCategoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldSetNestedCategoryParent()
    {
        $parent = new NestedCategory();
        $parent->setName('parent');

        $category = new NestedCategory();
        $category->setName('child');

        $category->setParent($parent);

        $this->assertEquals('parent', $category->getParent()->getName());
    }

    /**
     * @test
     * @expectedException PHPUnit_Framework_Error
     */
    public function shouldNotSetNotNestedCategoryParent()
    {
        $parent = $this->getMockForAbstractClass('Sylius\Bundle\CategorizerBundle\Model\Category');

        $category = new NestedCategory();
        $category->setName('child');

        $category->setParent($parent);
    }

    /**
     * @test
     */
    public function shouldSetChildrenCollection()
    {
        $child = new NestedCategory();
        $child->setName('child');

        $children = new ArrayCollection();
        $children->add($child);

        $category = new NestedCategory();
        $category->setChildren($children);

        $children = $category->getChildren();

        $this->assertEquals('child', $children[0]->getName());
    }

    /**
     * @test
     */
    public function shouldHasChildren()
    {
        $child = new NestedCategory();
        $child->setName('child');

        $children = new ArrayCollection();
        $children->add($child);

        $category = new NestedCategory();
        $category->setChildren($children);

        $this->assertTrue($category->hasChildren());
    }

    /**
     * @test
     */
    public function shouldNotHasChildren()
    {
        $category = new NestedCategory();

        $this->assertFalse($category->hasChildren());
    }
}
