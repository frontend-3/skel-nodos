<?php
/**
 * This file is part of Florence2 Zend Framework 2 module.
 */

namespace Florence2\Validator;

use Zend\Validator\AbstractValidator;

/**
 * DatepickerDate validator
 * 
 * Returns valid if the date conforms to a certain format (by default:
 * dd/mm/yyyy) and if it's also a valid date.
 * 
 * @package Florence2
 * @version v1.0.0
 * @author Jaime G. Wong <j@jgwong.org>
 */
class DatepickerDate extends AbstractValidator
{
    const INVALID = 'invalid';
    
    protected $messageTemplates = array(
        self::INVALID => 'La fecha es inválida',
    );
    
    protected $options = [
        'dateRegexp' => "/\A([0-9]{2})\/([0-9]{2})\/([0-9]{4})\Z/",
    ];
    
    
    public function isValid($value)
    {
        $this->setValue($value);
        
        if (!preg_match($this->options['dateRegexp'], $value, $m)) {
            $this->error(self::INVALID);
            return false;
        }
        
        $md = $m[1];
        $mm = $m[2];
        $my = $m[3];
        
        if (checkdate($mm, $md, $my) == false) {
            $this->error(self::INVALID);
            return false;
            
        } else {
            return true;
        }
    }
}

