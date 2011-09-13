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

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Category model.
 * 
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
abstract class Category implements CategoryInterface
{
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
     * Category items.
     * 
     * @var array
     */
    protected $items;
    
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
        $this->incrementUpdatedAt();
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
    
    function getItems()
    {
        return $this->items;
    }
    
    function setItems(array $items)
    {
        $this->items = $items;
    }
    
    function addItem(CategoryItemInterface $item)
    {
        if (!$this->hasItem($item)) {
            $this->items[] = $item;
        }
    }
    
    function removeItem(CategoryItemInterface $item)
    {
        if ($this->hasItem($item)) {
            
        }
    }
    
    function hasItem(CategoryItemInterface $item)
    {
        return in_array($item, $this->items);
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function incrementCreatedAt()
    {
        if (null == $this->createdAt) {
            $this->createdAt = new \DateTime;
        }
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    
    public function incrementUpdatedAt()
    {
        $this->updatedAt = new \DateTime();
    }
    
    public function __toString()
    {
        return $this->getName();
    }
}
