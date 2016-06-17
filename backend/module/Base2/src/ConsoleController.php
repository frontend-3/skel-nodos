<?php
/**
 * This file is part of Base2 Zend Framework 2 module.
 */

namespace Base2;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\Console\Exception\RuntimeException;

/**
 * Console commands
 * 
 * @version v1.3.0
 * @author Jaime G. Wong <jaime.wong@nodosdigital.pe>
 */
class ConsoleController extends AbstractActionController
{
    /**
     * Set Environment
     * 
     * @return void
     * @since v1.1.0
     */
    public function setEnvironmentAction()
    {
        $file        = '.environment';
        $console     = $this->getServiceLocator()->get('console');
        $environment = $this->getRequest()->getParam('environment');
        
        if ($environment == 'status') {
            $env = trim(file_get_contents($file));
            $console->write('Environment is currently set to ');
            $console->writeLine(strtoupper($env), 4);
            
        } else {
            $f = fopen($file, 'w');
            fwrite($f, $environment);
            fclose($f);
            
            $this->deleteModuleCacheFiles();
            
            $console->write('Environment set to ');
            $console->writeLine(strtoupper($environment), 4);
        }
    }
    
    
    /**
     * Delete ZF2's Module Config Cache files
     *
     * @return void
     * @since v1.1.0
     */
    protected function deleteModuleCacheFiles()
    {
        $files = [
            'data/cache/module-classmap-cache.module_map.php',
            'data/cache/module-config-cache.app_config.php',
        ];
        
        foreach ($files as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }
    
    
    /**
     * Drop the user into a psysh console. Nifty.
     *
     * @return void
     * @since v1.3.0
     */
    public function consoleAction()
    {
        pcntl_exec('vendor/bin/psysh', ['--config', 'module/Base2/src/psysh.config.php']);
    }
    
    
    /**
     * Drop the user into a MySQL console
     *
     * @return void
     * @since v1.3.0
     */
    public function mysqlAction()
    {
        $console  = $this->getServiceLocator()->get('console');
        $dbConfig = 'config/autoload/database.php';
        
        if (!file_exists($dbConfig)) {
            $console->writeLine('Database config file not found! Have you configured your database yet?');
            return;
        }
        
        include($dbConfig);
        
        pcntl_exec('/usr/bin/env', ['mysql', '-u', $username, '-p' . $password, $database]);
    }
}

