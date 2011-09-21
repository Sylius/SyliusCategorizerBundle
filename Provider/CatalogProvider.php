<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CatalogBundle\Provider;

/**
 * Catalog configuration provider.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class CatalogProvider
{
    protected $class;
    protected $catalogs;
    
    public function __construct($class, array $catalogs)
    {
        $this->class = $class;
        $this->catalogs = $catalogs;
    }
    
    /**
     * Returns catalog object for give alias.
     * 
     * @param string $alias
     */
    public function getCatalog($alias)
    {
        if ($this->hasCatalog($alias)) {
            $class = $this->class;
            return new $class($alias, $this->catalogs[$alias]);
        }
        
        throw new \InvalidArgumentException(sprintf('Catalog with alias "%s" does not exist.', $alias));
    }
    
    /**
     * Sets catalog configuration.
     * 
     * @param string $alias
     * @param array  $catalog
     */
    public function setCatalog($alias, array $catalog)
    {
        if ($this->hasCatalog($alias)) {
            throw new \InvalidArgumentException(sprintf('Catalog with alias "%s" exists.', $alias));
        }
        
        $this->catalogs[$alias] = $catalog;
    }
    
    public function hasCatalog($alias)
    {
        return isset($this->catalogs[$alias]);
    }
}