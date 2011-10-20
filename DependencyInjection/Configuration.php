<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CatalogBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * This class contains the configuration information for the bundle.
 *
 * This information is solely responsible for how the different configuration
 * sections are normalized, and merged.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree.
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sylius_catalog');

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('driver')->cannotBeOverwritten()->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('engine')->defaultValue('twig')->end()
            ->end();
        
        $this->addCatalogsSection($rootNode);
        $this->addClassesSection($rootNode);

        return $treeBuilder;
    }

	/**
     * Adds `catalogs` section.
     */
    private function addCatalogsSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('catalogs')
                    ->useAttributeAsKey('alias')
                    ->requiresAtLeastOneElement()
                    ->addDefaultsIfNotSet()
                    ->prototype('array')
                        ->children()
                            ->scalarNode('mode')->defaultValue('S')->end()
                            ->scalarNode('property')->defaultValue('items')->end()
                            ->scalarNode('sorter')->defaultValue(null)->end()
                            ->arrayNode('classes')
                                ->children()
                                    ->scalarNode('model')->isRequired()->cannotBeEmpty()->end()
                                    ->scalarNode('form')->defaultValue('Sylius\\Bundle\\CatalogBundle\\Form\\Type\\CategoryFormType')->end()
                                ->end()
                            ->end()
                            ->arrayNode('templates')
                                ->children()
                                    ->arrayNode('backend')
                                        ->addDefaultsIfNotSet()
                                        ->children()
                                            ->scalarNode('list')->defaultValue('SyliusCatalogBundle:Backend/Category:list.html.twig')->end()
                                            ->scalarNode('show')->defaultValue('SyliusCatalogBundle:Backend/Category:show.html.twig')->end()
                                            ->scalarNode('create')->defaultValue('SyliusCatalogBundle:Backend/Category:create.html.twig')->end()
                                            ->scalarNode('update')->defaultValue('SyliusCatalogBundle:Backend/Category:update.html.twig')->end()
                                        ->end()
                                    ->end()
                                    ->arrayNode('frontend')
                                        ->addDefaultsIfNotSet()
                                        ->children()
                                            ->scalarNode('list')->defaultValue('SyliusCatalogBundle:Backend/Category:list.html.twig')->end()
                                            ->scalarNode('show')->defaultValue('SyliusCatalogBundle:Backend/Category:show.html.twig')->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
    
    /**
     * Adds `classes` section.
     */
    private function addClassesSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('classes')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('model')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('catalog')->defaultValue('Sylius\\Bundle\\CatalogBundle\\Model\\Catalog')->end()
                            ->end()
                        ->end()
                        ->arrayNode('controller')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('backend')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('category')->defaultValue('Sylius\Bundle\\CatalogBundle\\Controller\Backend\\CategoryController')->end()
                                    ->end()
                                ->end()
                                ->arrayNode('frontend')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('category')->defaultValue('Sylius\Bundle\\CatalogBundle\\Controller\Frontend\\CategoryController')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('manipulator')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('category')->defaultValue('Sylius\\Bundle\\CatalogBundle\\Manipulator\\CategoryManipulator')->end()
                            ->end()
                        ->end()
                        ->arrayNode('inflector')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('slugizer')->defaultValue('Sylius\Bundle\\CatalogBundle\\Inflector\\Slugizer')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
