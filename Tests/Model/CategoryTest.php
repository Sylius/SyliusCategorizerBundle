<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CatalogBundle\Tests\Model;

use Sylius\Bundle\CatalogBundle\Model\Category;

class CategoryTest extends \PHPUnit_Framework_TestCase
{
    public function testName()
    {
        $product = $this->getCategory();
        $this->assertNull($product->getName());

        $product->setName('testing product');
        $this->assertEquals('testing product', $product->getName());
    }
    
    public function testSlug()
    {
        $product = $this->getCategory();
        $this->assertNull($product->getSlug());
    
        $product->setSlug('testing-product');
        $this->assertEquals('testing-product', $product->getSlug());
    }

    protected function getCategory()
    {
        return $this->getMockForAbstractClass('Sylius\Bundle\CatalogBundle\Model\Category');
    }
}
