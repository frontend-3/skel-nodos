<?php

namespace Base\InputFilter;
use Zend\Validator\AbstractValidator;

class DatepickerDate extends AbstractValidator {
    const INVALID = 'invalid';
    
    protected $messageTemplates = array(
        self::INVALID => 'La fecha es inválida',
    );
    
    
    public function isValid($value) {
        $this->setValue($value);
        
        if(!preg_match("/\A([0-9]{2})\/([0-9]{2})\/([0-9]{4})\Z/", $value, $m)) {
            $this->error(self::INVALID);
            return false;
        }
        
        $md = $m[1];
        $mm = $m[2];
        $my = $m[3];
        
        if(checkdate($mm, $md, $my) == false) {
            $this->error(self::INVALID);
            return false;
        }
        else {
            return true;
        }
    }
}

?>
