<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CategorizerBundle\Tests\Inflector;

use Sylius\Bundle\CategorizerBundle\Inflector\Slugizer;

/**
 * Slugizer test.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 * @author Саша Стаменковић <umpirsky@gmail.com>
 */
class SlugizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getStringsAndExpectedSlugs
     *
     * @param string $string       Original string
     * @param string $expectedSlug Expected slugized string
     */
    public function testSlugize($string, $expectedSlug)
    {
        $slugizer = new Slugizer();
        $this->assertEquals($expectedSlug, $slugizer->slugize($string));
    }

    public function getStringsAndExpectedSlugs()
    {
        return array(
            array('#example String!!!,,,...',     'example-string'),
            array('example string          test', 'example-string-test'),
            array('vrući-ćevapčići',              'vruci-cevapcici')
        );
    }
}
