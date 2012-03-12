<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CategorizerBundle\Tests\Registry;

use Sylius\Bundle\CategorizerBundle\Registry\CatalogRegistry;
use Sylius\Bundle\CategorizerBundle\Registry\Catalog;

/**
 * Category registry test.
 *
 * @author Leszek Prabucki <leszek.prabucki@gmail.com>
 */
class CategoryRegistryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldGetCatalogWhenHaveConfigurationForIt()
    {
        $catalogRegistry = new CatalogRegistry(array('catalog1' => array('option1' => 'value1')));
        $catalog = $catalogRegistry->getCatalog('catalog1');

        $this->assertInstanceOf('Sylius\Bundle\CategorizerBundle\Registry\Catalog', $catalog);
        $this->assertEquals('catalog1', $catalog->getAlias());
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function shouldNotGetCatalogWhenNotHaveConfigurationForIt()
    {
        $catalogRegistry = new CatalogRegistry(array('catalog1' => array('option1' => 'value1')));
        $catalogRegistry->getCatalog('catalog2');
    }

    /**
     * @test
     */
    public function shouldSetCatalog()
    {
        $catalogRegistry = new CatalogRegistry(array('catalog1' => array('option1' => 'value1')));
        $catalogRegistry->setCatalog('catalog2', new Catalog('catalog2', array()));
        $catalog = $catalogRegistry->getCatalog('catalog2');

        $this->assertInstanceOf('Sylius\Bundle\CategorizerBundle\Registry\Catalog', $catalog);
        $this->assertEquals('catalog2', $catalog->getAlias());
    }

    /**
     * @test
     */
    public function shouldSetCatalogWithDifferentAlias()
    {
        $catalogRegistry = new CatalogRegistry(array('catalog1' => array('option1' => 'value1')));
        $catalogRegistry->setCatalog('catalog2', new Catalog('differentAlias', array()));
        $catalog = $catalogRegistry->getCatalog('catalog2');

        $this->assertInstanceOf('Sylius\Bundle\CategorizerBundle\Registry\Catalog', $catalog);
        $this->assertEquals('differentAlias', $catalog->getAlias());
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function shouldGetCatalogWhichWasSetForDifferentAlias()
    {
        $catalogRegistry = new CatalogRegistry(array('catalog1' => array('option1' => 'value1')));
        $catalogRegistry->setCatalog('catalog1', new Catalog('catalog1', array()));
    }

    /**
     * @test
     */
    public function shouldGuessFromCatalog()
    {
        $catalogRegistry = new CatalogRegistry(array());
        $catalog = $catalogRegistry->guessCatalog(new Catalog('catalog1', array()));

        $this->assertEquals('catalog1', $catalog->getAlias());
    }

    /**
     * @test
     */
    public function shouldGuessFromModelConfig()
    {
        $catalogRegistry = new CatalogRegistry(array('test' => array('model' => 'modelClass')));
        $catalog = $catalogRegistry->guessCatalog('test');

        $this->assertEquals('test', $catalog->getAlias());
    }

    /**
     * @test
     */
    public function shouldGuessFromModelCategory()
    {
        $category = $this->getMock('Sylius\Bundle\CategorizerBundle\Model\CategoryInterface');

        $catalogRegistry = new CatalogRegistry(array('test' => array('model' => get_class($category))));
        $catalog = $catalogRegistry->guessCatalog($category);

        $this->assertEquals('test', $catalog->getAlias());
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function shouldNotGuessFromNothing()
    {
        $catalogRegistry = new CatalogRegistry(array());
        $catalogRegistry->guessCatalog('modelClass');
    }
}
