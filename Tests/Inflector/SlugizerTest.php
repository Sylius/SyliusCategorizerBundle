<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CatalogBundle\Tests\Inflector;

use Sylius\Bundle\CatalogBundle\Inflector\Slugizer;

/**
 * SlugizerTest.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 * @author Саша Стаменковић <umpirsky@gmail.com>
 */
class SlugizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider testSlugizeProvider
     *
     * @param string $string original string
     * @param string $slug expected slugized string
     */
    public function testSlugize($string, $slug)
    {
        $slugizer = new Slugizer();
        $this->assertEquals($slugizer->slugize($string), $slug);
    }

    public function testSlugizeProvider()
    {
        return array(
            array('#example String!!!,,,...', 'example-string'),
            array('example string          test', 'example-string-test'),
            array('vrući-ćevapčići', 'vruci-cevapcici')
        );
    }
}
