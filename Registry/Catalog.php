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

/**
 * Default catalog implementation.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class Catalog implements CatalogInterface
{
    /**
     * Catalog alias.
     *
     * @var string
     */
    protected $alias;

    /**
     * Catalog options.
     *
     * @var array
     */
    protected $options;

    /**
     * @param string $alias   The alias of the catalog
     * @param array  $options Catalog options
     */
    public function __construct($alias, array $options)
    {
        $this->alias = $alias;
        $this->options = $options;
    }

    /**
     * Returns alias name.
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Sets the alias name.
     *
     * @param string $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    /**
     * Returns all catalog options.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Sets catalog options.
     *
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    public function getOption($key, $default = null)
    {
        if ($this->hasOption($key)) {

            return $this->options[$key];
        }

        if ($default !== null) {

            return $default;
        }

        throw new \InvalidArgumentException(sprintf('Requested option "%s" for catalog with alias "%s" does not exist.', $key, $this->getAlias()));
    }

    public function setOption($key, $value)
    {
        $this->options[$key] = $value;
    }

    public function hasOption($key)
    {
        return isset($this->options[$key]);
    }
}

