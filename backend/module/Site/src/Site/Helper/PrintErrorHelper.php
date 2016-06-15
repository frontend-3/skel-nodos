<?php
/**
 * Created by PhpStorm.
 * User: Flash
 * Date: 24/06/14
 * Time: 09:55 AM
 */


namespace Site\Helper;

use Zend\View\Helper\AbstractHelper;

class PrintErrorHelper  extends AbstractHelper{

    public function __invoke($e) {
        $maskError = '<label class="msg" for="%s">%s</label>';
        $hasError = (bool) count($e->getMessages());
        $firstError = $hasError ? array_values($e->getMessages())[0] : '';
        $errorMessage = $hasError ? sprintf($maskError, $e->getName(), $firstError) : '';
        return $errorMessage;
    }
} 