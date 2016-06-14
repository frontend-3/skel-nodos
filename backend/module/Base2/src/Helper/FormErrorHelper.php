<?php
/**
 * This file is part of Base2 Zend Framework 2 module.
 */

namespace Base2\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * FormErrorHelper
 * 
 * @see AbstractHelper
 * @package Base2
 * @version v1.0.0
 * @author Jaime Wong <jaime.wong@nodosdigital.pe>
 */
class FormErrorHelper extends AbstractHelper
{
    public function __invoke($e)
    {
        $maskError    = '<label class="msg" for="%s">%s</label>';
        $hasError     = (bool) count($e->getMessages());
        $firstError   = ($hasError ? array_values($e->getMessages())[0] : '');
        $errorMessage = ($hasError ? sprintf($maskError, $e->getName(), $firstError) : '');
        
        return $errorMessage;
    }
} 

