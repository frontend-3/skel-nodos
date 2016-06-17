<?php
/**
 * This file is part of BaseTest Zend Framework 2 module.
 */

namespace BaseTest;

use Mockery as m;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

/**
 * Helpers to instantiate common Mocks used in Unit Testing
 * 
 * @package BaseTest
 * @version v1.0.1
 * @author Jaime Wong <jaime.wong@nodosdigital.pe>
 */
trait Helpers
{
    protected $serviceManager;
    
    public function getServiceManager()
    {
        if (is_null($this->serviceManager)) {
            $sm = new ServiceManagerGrabber();
            $this->serviceManager = $sm->getServiceManager();
        }
        
        return $this->serviceManager;
    }
    
    
    /**
     * Get a Doctrine Entity Manager mock
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManagerMock()
    {
        return m::mock('Doctrine\ORM\EntityManager');
    }
    
    
    /**
     * Get a DoctrineObject Hydrator
     * 
     * This Hydrator uses a real Doctrine Entity Manager to do its job.
     * I'm kinda sure it doesn't impact or hit the database.
     * 
     * @return DoctrineHydrator
     */
    public function getDoctrineHydrator()
    {
        $entityManager = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        return new DoctrineHydrator($entityManager);
    }
}

