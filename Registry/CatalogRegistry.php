<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CategorizerBundle\Registry;

use Sylius\Bundle\CategorizerBundle\Model\CategoryInterface;
use Sylius\Bundle\CategorizerBundle\Model\CategoryManagerInterface;

/**
 * Catalog configuration provider.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class CatalogRegistry
{
    protected $configuration;
    protected $catalogs;

    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
        $this->catalogs = array();
    }

    /**
     * Returns catalog object for give alias.
     *
     * @param string $alias
     */
    public function getCatalog($alias)
    {
        if ($this->hasCatalog($alias)) {
            if (!isset($this->catalogs[$alias])) {
                return $this->catalogs[$alias] = new Catalog($alias, $this->configuration[$alias]);
            }

            return $this->catalogs[$alias];
        }

        throw new \InvalidArgumentException(sprintf('Catalog with alias "%s" does not exist.', $alias));
    }

    /**
     * Sets catalog.
     *
     * @param string           $alias
     * @param CatalogInterface $catalog
     */
    public function setCatalog($alias, CatalogInterface $catalog)
    {
        if ($this->hasCatalog($alias)) {
            throw new \InvalidArgumentException(sprintf('Catalog with alias "%s" already exists.', $alias));
        }

        $this->catalogs[$alias] = $catalog;
        //need to set when we want to pass hasCatalog check
        $this->configuration[$alias] = array();
    }

    /**
     * Checks whether the catalog has configuration.

     * @param string $alias
     */
    public function hasCatalog($alias)
    {
        return isset($this->configuration[$alias]);
    }

    /**
     * Returns catalog for given category.
     *
     * @param string|CategoryInterface|CatalogInterface $category
     *
     * @return CatalogInterface
     */
    public function guessCatalog($guessable)
    {
        if ($guessable instanceof CatalogInterface) {

            return $guessable;
        } elseif (is_string($guessable) && $this->hasCatalog($guessable)) {

            return $this->getCatalog($guessable);
        } elseif ($guessable instanceof CategoryInterface) {
            $class = get_class($guessable);
        } else {
            $class = $guessable;
        }

        foreach ($this->configuration as $alias => $cfg) {
            if ($class === $cfg['model']) {

                return $this->getCatalog($alias);
            }
        }

        throw new \InvalidArgumentException('Failed to guess the catalog.');
    }
}
