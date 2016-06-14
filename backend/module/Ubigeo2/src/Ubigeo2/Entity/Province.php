<?php
/**
 * This file is part of Ubigeo2 Zend Framework 2 module.
 */

namespace Ubigeo2\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Ubigeo Province
 * 
 * @package Ubigeo2
 * @version v1.0.0
 * @author Jaime Wong <jaime.wong@nodosdigital.pe>
 * @ORM\Entity
 * @ORM\Table(name="ubigeo_provinces")
 */
class Province
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
     * @var Department Department
     * @ORM\ManyToOne(targetEntity="Department", inversedBy="provinces")
     * @ORM\JoinColumn(name="ubigeo_department_id", referencedColumnName="id")
     */
    protected $department;
    
    
    /**
     * @var ArrayCollection Districts
     * @ORM\OneToMany(targetEntity="District", mappedBy="province")
     */
    protected $districts;

    /**
     * Get id
     *
     * @return integer
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
     * @return Province
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
     */
    public function getName()
    {
        return $this->name;
    }
    
    
    /**
     * Set Department
     *
     * @param Department $department
     *
     * @return Province
     */
    public function setDepartment(Department $department = null)
    {
        $this->department = $department;
        return $this;
    }
    
    
    /**
     * Get Department
     *
     * @return Department
     */
    public function getDepartment()
    {
        return $this->department;
    }
    
    
    /**
     * Get Districts
     * 
     * @return ArrayCollection
     * @since v1.0.0
     */
    public function getDistricts()
    {
        return $this->districts;
    }
    
    
    /**
     * Construct
     * 
     * @return void
     * @since v1.0.0
     */
    public function __construct()
    {
        $this->districts = new ArrayCollection();
    }
}

