<?php

namespace Base\InputFilter;
use Zend\Validator\AbstractValidator;

class BadLanguage extends AbstractValidator {
    const INVALID = 'invalid';
    
    protected $messageTemplates = array(
        self::INVALID => 'Creo que deberías moderar tu lenguaje',
    );
    
    
    public function isValid($value) {
        $this->setValue($value);
        
        include(__DIR__ . '/badwords.php');
        
        $value = preg_replace('/[^a-z ]/', '', mb_strtolower($value));
        $words = explode(' ', $value);
        
        foreach($words as $w) {
            if(in_array($w, $bad_words)) {
                $this->error(self::INVALID);
                return false;
            }
        }
        
        foreach($bad_phrases as $phrase) {
            if(strpos($value, $phrase) !== false) {
                $this->error(self::INVALID);
                return false;
            }
        }
        
        return true;
    }
}

?>
