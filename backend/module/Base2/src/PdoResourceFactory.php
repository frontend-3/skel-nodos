<?php
/**
 * This file is part of Base2 Zend Framework 2 module.
 */

namespace Base2;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;

/**
 * PDO Resource Factory
 * 
 * Returns the DB Adapter PDO resource.
 * 
 * @package Base2
 * @version v1.0.0
 * @author Jaime Wong <jaime.wong@nodosdigital.pe>
 */
class PdoResourceFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return \PDO resource
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $dbAdapter = $services->get('Zend\Db\Adapter\Adapter');
        
        $pdo = $dbAdapter->getDriver()->getConnection()->getResource();
        if (!$pdo instanceof \PDO) {
            throw new ServiceNotCreatedException('Connection resource must be an instance of PDO');
        }
        return $pdo;
    }
} 

