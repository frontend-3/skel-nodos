<?php

namespace Site\Helper;

use Zend\View\Helper\AbstractHelper;

class CamelCaseHelper  extends AbstractHelper{

    public function __invoke($string, $capitalizeFirstCharacter = false) {
       $str = str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
	    if (!$capitalizeFirstCharacter) {
	        $str[0] = strtolower($str[0]);
	    }
	    return $str;
    }
} 