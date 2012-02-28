<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CategorizerBundle\Tests\EventDispatcher\Event;

use Sylius\Bundle\CategorizerBundle\EventDispatcher\Event\FilterCategoryEvent;

/**
 * Category filtering event test.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class FilterCategoryEventTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $category = $this->getMock('Sylius\Bundle\CategorizerBundle\Model\CategoryInterface');
        $catalog = $this->getMock('Sylius\Bundle\CategorizerBundle\Registry\CatalogInterface');

        $event = new FilterCategoryEvent($category, $catalog);

        $this->assertEquals($category, $event->getCategory());
        $this->assertEquals($catalog, $event->getCatalog());
    }
}
