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

use Sylius\Bundle\CategorizerBundle\Registry\Catalog;

/**
 * Category registry test.
 *
 * @author Leszek Prabucki <leszek.prabucki@gmail.com>
 */
class CategoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldSetAliasWhenCreatingObject()
    {
        $catalog = new Catalog('testAlias', array());

        $this->assertEquals('testAlias', $catalog->getAlias());
    }

    /**
     * @test
     */
    public function shouldSetAliasAfterCreationOfObject()
    {
        $catalog = new Catalog('testAlias', array());
        $catalog->setAlias('newTestAlias');

        $this->assertEquals('newTestAlias', $catalog->getAlias());
    }

    /**
     * @test
     */
    public function shouldSetOptionsWhenCreatingObject()
    {
        $catalog = new Catalog(null, array('option1' => 'value1', 'option2' => 'value2'));

        $this->assertEquals(array('option1' => 'value1', 'option2' => 'value2'), $catalog->getOptions());
    }

    /**
     * @test
     */
    public function shouldSetOptionsAfterCreationOfObject()
    {
        $catalog = new Catalog(null, array());
        $catalog->setOptions(array('option1' => 'value1'));

        $this->assertEquals(array('option1' => 'value1'), $catalog->getOptions());
    }

    /**
     * @test
     */
    public function shouldGetStoredOption()
    {
        $catalog = new Catalog(null, array('option1' => 'value1', 'option2' => 'value2'));

        $this->assertEquals('value2', $catalog->getOption('option2'));
    }

    /**
     * @test
     */
    public function shouldGetDefaultValueWhenOptionNotStored()
    {
        $catalog = new Catalog(null, array('option1' => 'value1', 'option2' => 'value2'));

        $this->assertEquals('test123', $catalog->getOption('option3', 'test123'));
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function shouldNotGetOptionWhenNotStored()
    {
        $catalog = new Catalog(null, array('option1' => 'value1', 'option2' => 'value2'));

        $catalog->getOption('option3');
    }

    /**
     * @test
     */
    public function shouldSetOption()
    {
        $catalog = new Catalog(null, array());
        $catalog->setOption('option3', 'test123');

        $this->assertEquals('test123', $catalog->getOption('option3'));
    }
}
