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
 * Category model interface.
 * 
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
interface CategoryInterface
{
    function getId();
    function getName();
    function setName($name);
    function getSlug();
    function setSlug($slug);
    function getPosition();
    function setPosition($position);
    function incrementPosition();
    function decrementPosition();
    function getItems();
    function setItems(array $items);
    function addItem(CategoryItemInterface $item);
    function removeItem(CategoryItemInterface $item);
    function hasItem(CategoryItemInterface $item);
    function getCreatedAt();
    function incrementCreatedAt();
    function getUpdatedAt();
    function incrementUpdatedAt();
}
