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
 * Category model.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
abstract class Category implements CategoryInterface
{
    /**
     * Category id.
     *
     * @var integer
     */
    protected $id;

    /**
     * Category displayed name.
     *
     * @var string
     */
    protected $name;

    /**
     * Slugized category name.
     *
     * @var string
     */
    protected $slug;

    /**
     * Position in categories list.
     *
     * @var integer
     */
    protected $position;

    /**
     * Creation time.
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * Modification time.
     *
     * @var \DateTime
     */
    protected $updatedAt;

    public function __construct()
    {
        $this->position = 0;
        $this->createdAt = new \DateTime;
        $this->updatedAt = new \DateTime;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition($position)
    {
        $this->position = $position;
    }

    public function incrementPosition()
    {
        $this->position++;
    }

    public function decrementPosition()
    {
        $this->position--;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function incrementCreatedAt()
    {
        $this->createdAt = new \DateTime;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function incrementUpdatedAt()
    {
        $this->updatedAt = new \DateTime;
    }
}
