<?php
/**
 * This file is part of Base2 Zend Framework 2 module.
 */

namespace Base2;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Session\Container;

/**
 * Session Controller Plugin
 * 
 * This plugin provides a quick interface to Session handling, which requires
 * instancing a Container with a given namespace. No more need to write
 * repetitive code for such a common task.
 *
 * @package Base2
 * @version v1.0.0
 * @author Jaime Wong <jaime.wong@nodosdigital.pe>
 */
class SessionPlugin extends AbstractPlugin
{
    /**
     * @var Container Session container
     */
    protected $container;
    
    public function __construct(Container $container = null) {
        if (is_null($container)) {
            $this->container = new Container('app');
        } else {
            $this->container = $container;
        }
    }
    
    /**
     * Sets a new value in our default session container.
     * 
     * @param string $key
     * @param mixed $value
     * @return void
     * @since v1.0.0
     */
    public function set($key, $value){
        $this->container->offsetSet($key, $value);
    }
    
    
    /**
     * Removes a value in our default session container.
     * 
     * @param string $key
     * @return void
     * @since v1.0.0
     */
    public function remove($key){
        $this->container->offsetUnset($key);
    }
    
    
    /**
     * Gets a value stored in our default session container.
     * 
     * @param string $key
     * @return mixed
     * @throw \OutOfRangeException
     * @since v1.0.0
     */
    public function get($key){
        if ($this->has($key)) {
            return $this->container->offsetGet($key);
            
        } else {
            throw new \OutOfRangeException('Session index "' . $key . '" does not exist');
        }
    }
    
    
    /**
     * Returns true if key exists in default session container
     * 
     * @param string $key
     * @return boolean
     * @since v1.0.0
     */
    public function has($key){
        return $this->container->offsetExists($key);
    }
    
}

