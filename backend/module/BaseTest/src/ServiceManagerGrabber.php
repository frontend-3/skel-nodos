<?php
/**
 * This file is part of BaseTest Zend Framework 2 module.
 */

namespace BaseTest;
 
/**
 * ServiceManagerGrabber
 *
 * @package BaseTest
 * @version v1.0.0
 * @author Jaime Wong <jaime.wong@nodosdigital.pe>
 */
class ServiceManagerGrabber
{
    protected static $serviceManager = null;
     
    static public function setServiceManager($sm)
    {
        self::$serviceManager = $sm;
    }
    
    
    static public function getServiceManager()
    {
        return self::$serviceManager;
    }
}


