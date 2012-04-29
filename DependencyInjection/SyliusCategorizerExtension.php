<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CategorizerBundle\DependencyInjection;

use Sylius\Bundle\CategorizerBundle\SyliusCategorizerBundle;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;

/**
 * Catalog extension.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class SyliusCategorizerExtension extends Extension
{
    /**
     * @see Extension/Symfony\Component\DependencyInjection\Extension.ExtensionInterface::load()
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $config);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/container'));

        if (!in_array($config['driver'], SyliusCategorizerBundle::getSupportedDrivers())) {
            throw new \InvalidArgumentException(sprintf('Driver "%s" is unsupported for this extension.', $config['driver']));
        }
        if (!in_array($config['engine'], array('twig', 'php'))) {
            throw new \InvalidArgumentException(sprintf('Engine "%s" is unsupported for this extension.', $config['engine']));
        }

        $loader->load(sprintf('driver/%s.xml', $config['driver']));

        $container->setParameter('sylius_categorizer.driver', $config['driver']);
        $container->setParameter('sylius_categorizer.engine', $config['engine']);

        $remappedCatalogsConfiguration = array();

        foreach($config['catalogs'] as $alias => $catalog) {
            $remappedCatalogConfiguration = array(
                'alias'              => $alias,
                'model'              => $catalog['model'],
                'form'               => $catalog['form'],
                'property'           => $catalog['property'],
                'templates.frontend' => $catalog['templates']['frontend'],
                'templates.backend'  => $catalog['templates']['backend'],
                'pagination'         => !$catalog['pagination']['disable'],
                'pagination.mpp'     => $catalog['pagination']['mpp']
            );

            $remappedCatalogsConfiguration[$alias] = $remappedCatalogConfiguration;
        }

        $container->setParameter('sylius_categorizer.catalogs', $remappedCatalogsConfiguration);

        $configurations = array(
            'controllers',
            'forms',
            'manipulators',
            'registry'
        );

        foreach($configurations as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }

        $this->remapParametersNamespaces($config['classes'], $container, array(
            'manipulator' => 'sylius_categorizer.manipulator.%s.class',
            'model'       => 'sylius_categorizer.model.%s.class',
            'inflector'   => 'sylius_categorizer.inflector.%s.class'
        ));

        $this->remapParametersNamespaces($config['classes']['controller'], $container, array(
            'backend'  => 'sylius_categorizer.controller.backend.%s.class',
            'frontend' => 'sylius_categorizer.controller.frontend.%s.class'
        ));
    }

    /**
     * Remaps parameters.
     *
     * @param array            $config
     * @param ContainerBuilder $container
     * @param array            $map
     */
    protected function remapParameters(array $config, ContainerBuilder $container, array $map)
    {
        foreach ($map as $name => $paramName) {
            if (isset($config[$name])) {
                $container->setParameter($paramName, $config[$name]);
            }
        }
    }

    /**
     * Remaps parameter namespaces.
     *
     * @param array            $config
     * @param ContainerBuilder $container
     * @param array            $map
     */
    protected function remapParametersNamespaces(array $config, ContainerBuilder $container, array $namespaces)
    {
        foreach ($namespaces as $ns => $map) {
            if ($ns) {
                if (!isset($config[$ns])) {
                    continue;
                }
                $namespaceConfig = $config[$ns];
            } else {
                $namespaceConfig = $config;
            }
            if (is_array($map)) {
                $this->remapParameters($namespaceConfig, $container, $map);
            } else {
                foreach ($namespaceConfig as $name => $value) {
                    if (null !== $value) {
                        $container->setParameter(sprintf($map, $name), $value);
                    }
                }
            }
        }
    }
}
