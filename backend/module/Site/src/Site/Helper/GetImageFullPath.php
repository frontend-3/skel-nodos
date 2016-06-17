<?php

namespace Site\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;  
use Zend\ServiceManager\ServiceLocatorInterface;  

class GetImageFullPath  extends AbstractHelper implements ServiceLocatorAwareInterface{

	public function setServiceLocator(ServiceLocatorInterface $serviceLocator)  
    {  
        $this->serviceLocator = $serviceLocator;  
        return $this;  
    }  
    /** 
     * Get the service locator. 
     * 
     * @return \Zend\ServiceManager\ServiceLocatorInterface 
     */  
    public function getServiceLocator()  
    {  
        return $this->serviceLocator;  
    }  

    public function __invoke($asset,$full=true) {
        $asset = '/'.$asset;
    	$helper_name = $full ? 'ServerUrl' : 'basePath';
      	$helper = $this->getServiceLocator()->get($helper_name);
        return $helper->__invoke($asset);
    }
} 