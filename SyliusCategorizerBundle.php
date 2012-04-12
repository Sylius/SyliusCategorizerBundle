<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CategorizerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Catalorizer bundle.
 * It provides models and interfaces for category management.
 * Allows to configure many different categories sets for any model.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class SyliusCategorizerBundle extends Bundle
{
    // Bundle drivers list.
    const DRIVER_DOCTRINE_ORM         = 'doctrine/orm';
    const DRIVER_DOCTRINE_MONGODB_ODM = 'doctrine/mongodb-odm';
    const DRIVER_DOCTRINE_COUCHDB_ODM = 'doctrine/couchdb-odm';
    const DRIVER_PROPEL               = 'propel';

    /**
     * Return array of currently supported drivers.
     *
     * @return array
     */
    static public function getSupportedDrivers()
    {
        return array(
            self::DRIVER_DOCTRINE_ORM
        );
    }
}
