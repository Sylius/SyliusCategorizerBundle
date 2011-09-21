<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CatalogBundle\Model;

/**
 * Catalog representation interface.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
interface CatalogInterface
{
    function getAlias();
    function getOptions();
    function setOptions(array $options);
    function getOption($key);
    function setOption($key, $value);
    function hasOption($key);
}