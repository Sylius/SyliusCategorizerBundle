<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CategorizerBundle\Model;

/**
 * Category model interface.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
interface CategoryInterface
{
    /**
     * Get category id.
     *
     * @return mixed
     */
    function getId();

    /**
     * Set category id.
     *
     * @param mixed $id
     */
    function setId($id);

    /**
     * Get category name.
     *
     * @return string
     */
    function getName();

    /**
     * Set category name.
     *
     * @param string $name
     */
    function setName($name);

    /**
     * Get category slug.
     *
     * @return string
     */
    function getSlug();

    /**
     * Set category slug.
     *
     * @param string $slug
     */
    function setSlug($slug);

    /**
     * Get category position in list.
     *
     * @return integer
     */
    function getPosition();

    /**
     * Set category position in list.
     *
     * @param integer $position
     */
    function setPosition($position);

    /**
     * Increment position by one.
     */
    function incrementPosition();

    /**
     * Decrement position by one.
     */
    function decrementPosition();

    /**
     * Generates category label to display in choice lists.
     *
     * @return string
     */
    function getLabel();

    /**
     * Get the creation time.
     *
     * @return DateTime
     */
    function getCreatedAt();

    /**
     * Set the creation time.
     *
     * @param DateTime $createdAt
     */
    function setCreatedAt(\DateTime $createdAt);

    /**
     * Set creation time to now.
     */
    function incrementCreatedAt();

    /**
     * Get the time of last update.
     *
     * @return DateTime
     */
    function getUpdatedAt();

    /**
     * Set the time of last update.
     *
     * @param DateTime $updatedAt
     */
    function setUpdatedAt(\DateTime $updatedAt);

    /**
     * Set the time of last update to now.
     */
    function incrementUpdatedAt();
}
