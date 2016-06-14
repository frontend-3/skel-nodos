<?php
/**
 * This file is part of Ubigeo2 Zend Framework 2 module.
 */

namespace Ubigeo2\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Ubigeo Department
 * 
 * @package Ubigeo2
 * @version v1.0.0
 * @author Jaime Wong <jaime.wong@nodosdigital.pe>
 * @ORM\Entity
 * @ORM\Table(name="ubigeo_departments")
 */
class Department
{
    /**
     * @var integer ID
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     */
     protected $id;
     
     /**
      * @var string Name
      * @ORM\Column(type="string")
      */
    protected $name;
    
    
    /**
     * @var ArrayCollection Provinces
     * @ORM\OneToMany(targetEntity="Province", mappedBy="department")
     */
    protected $provinces;
    
    
    /**
     * Get ID
     *
     * @return integer
     * @since v1.0.0
     */
    public function getId()
    {
        return $this->id;
    }
    
    
    /**
     * Set name
     *
     * @param string $name
     *
     * @return Department
     * @since v1.0.0
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
    
    /**
     * Get name
     *
     * @return string
     * @since v1.0.0
     */
    public function getName()
    {
        return $this->name;
    }
    
    
    /**
     * Get Provinces
     * 
     * @return void
     * @since v1.0.0
     */
    public function getProvinces()
    {
        return $this->provinces;
    }
    
    
    /**
     * Construct
     * 
     * @return void
     * @since v1.0.0
     */
    public function __construct()
    {
        $this->provinces = new ArrayCollection();
    }
}

