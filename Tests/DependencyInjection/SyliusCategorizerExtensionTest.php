<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CategorizerBundle\Tests\DependencyInjection;

use Sylius\Bundle\CategorizerBundle\DependencyInjection\SyliusCategorizerExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;

/**
 * DIC extension test.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class SyliusCatalogExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testLoadThrowsExceptionUnlessDriverSet()
    {
        $loader = new SyliusCategorizerExtension();
        $config = $this->getEmptyConfig();
        unset($config['driver']);
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testLoadThrowsExceptionUnlessDriverIsValid()
    {
        $loader = new SyliusCategorizerExtension();
        $config = $this->getEmptyConfig();
        $config['driver'] = 'foo';
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testLoadThrowsExceptionUnlessEngineIsValid()
    {
        $loader = new SyliusCategorizerExtension();
        $config = $this->getEmptyConfig();
        $config['engine'] = 'foo';
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testLoadThrowsExceptionUnlessAtLeastOneCatalogIsConfigured()
    {
        $loader = new SyliusCategorizerExtension();
        $config = $this->getEmptyConfig();
        unset($config['catalogs']['testing']);
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testUserLoadThrowsExceptionUnlessPropertySet()
    {
        $loader = new SyliusCategorizerExtension();
        $config = $this->getEmptyConfig();
        unset($config['catalogs']['testing']['property']);
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * getEmptyConfig
     *
     * @return array
     */
    protected function getEmptyConfig()
    {
        $yaml = <<<EOF
driver: doctrine/orm
catalogs:
    testing:
        property: tests
        model: Sylius\Bundle\CategorizerBundle\Entity\DefaultCategory
EOF;
        $parser = new Parser();

        return $parser->parse($yaml);
    }
}
