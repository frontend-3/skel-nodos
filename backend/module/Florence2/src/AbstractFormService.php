<?php
/**
 * This file is part of Florence2 Zend Framework 2 module.
 */

namespace Florence2;

use Zend\ServiceManager\ServiceLocatorInterface;
use Florence2\Florence2;

/**
 * Abstract Form Service
 *
 * @abstract
 * @package Florence2
 * @version v2.0.0
 * @author Jaime Wong <jaime.wong@nodosdigital.pe>
 */
abstract class AbstractFormService
{
    /**
     * @var ServiceLocatorInterface Service Manager
     */
    protected $serviceManager;
    
    
    public function __construct(ServiceLocatorInterface $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }
    
    
    /**
     * Get Service Manager
     * 
     * @return ServiceLocatorInterface
     * @since v1.0.0
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }
    
    
    /**
     * Set Service Manager
     * 
     * @param ServiceLocatorInterface $serviceManager
     * @return AbstractFormManager
     * @since v1.0.0
     */
    public function setServiceManager($serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }
}

