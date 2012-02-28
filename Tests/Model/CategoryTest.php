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

    protected function getCategory()
    {
        return $this->getMockForAbstractClass('Sylius\Bundle\CategorizerBundle\Model\Category');
    }
}
