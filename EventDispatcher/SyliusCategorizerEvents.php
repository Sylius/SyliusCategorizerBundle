<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CategorizerBundle\EventDispatcher;

/**
 * Events for catalog bundle.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
final class SyliusCategorizerEvents
{
    const CATEGORY_CREATE    = 'sylius_categorizer.event.category.create';
    const CATEGORY_UPDATE    = 'sylius_categorizer.event.category.update';
    const CATEGORY_DELETE    = 'sylius_categorizer.event.category.delete';
    const CATEGORY_MOVE_UP   = 'sylius_categorizer.event.category.move-up';
    const CATEGORY_MOVE_DOWN = 'sylius_categorizer.event.category.move-down';
}
