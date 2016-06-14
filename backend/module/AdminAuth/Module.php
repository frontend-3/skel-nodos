<?php

namespace AdminAuth;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function getServiceConfig()
    {
        return array(
            'factories'=>array(
                'ServiceAuthUser' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new AdminAuth\Model\AdminUserTable($dbAdapter);
                    return $table;
                },
                'ServiceAuthRole' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new AdminAuth\Model\AdminRoleTable($dbAdapter);
                    return $table;
                },
                'ServiceAuthPermission' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new AdminAuth\Model\AdminPermissionTable($dbAdapter);
                    return $table;
                },
                'ServiceAuthRolePermission' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new AdminAuth\Model\AdminRolePermissionTable($dbAdapter);
                    return $table;
                },
            ),
        );
    }
    
    
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    'Base' => dirname(__DIR__) . '/Base',
                    'AdminAuth' => __DIR__ . '/src/' . 'AdminAuth',
                ),
            ),
        );
    }
}

