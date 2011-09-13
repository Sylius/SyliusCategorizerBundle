<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CatalogBundle\EventDispatcher;

/**
 * Events for catalog bundle.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
final class SyliusCatalogEvents
{
    const CATEGORY_CREATE = 'sylius_catalog.event.category.create';
    const CATEGORY_UPDATE = 'sylius_catalog.event.category.update';
    const CATEGORY_DELETE = 'sylius_catalog.event.category.delete';
}
