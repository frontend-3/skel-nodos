<?php

namespace Base\InputFilter;
use Zend\Validator\AbstractValidator;

class CheckboxChecked extends AbstractValidator {
// Returns valid if the Checkbox is considered checked, by comparing it to its
// assigned checked value (specified in the options as 'checked_value').
    const UNCHECKED = 'unchecked';
    
    protected $messageTemplates = array(
        self::UNCHECKED => 'Debes marcar la casilla',
    );
    
    
    public function isValid($value) {
        $this->setValue($value);
        
        if($value != $this->getOption('checked_value') && $this->getoption('required') === true) {
            $this->error(self::UNCHECKED);
            return false;
        }
        else {
            return true;
        }
    }
}

?>
