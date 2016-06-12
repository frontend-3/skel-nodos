<?php

$defaults = array(
    'id'       => $name,
    'required' => true,
    'filters'  => array(
        'StripTags',
        'StringTrim',
    ),
);

if(isset($element['acts_as'])) {
    switch($element['acts_as']) {
        case 'name':
            $defaults['validators']['Alpha'] = '';
            break;
        
        case 'email':
            $defaults['validators']['EmailAddress'] = '';
            break;
        
        case 'number':
            $defaults['validators']['Digits'] = '';
            break;
        
        case 'dni':
            $defaults['min_length'] = 8;
            $defaults['max_length'] = 8;
            $defaults['validators']['StringLength'] = '';
            $defaults['validators']['Digits'] = '';
            break;
        
        case 'telephone':
            $defaults['min_length'] = 6;
            $defaults['max_length'] = 9;
            $defaults['validators']['StringLength'] = '';
            $defaults['validators']['Digits'] = '';
            break;
    }
}

if(isset($element['min_length']) || isset($element['max_length'])) {
    $defaults['validators']['StringLength'] = '';
}

if(isset($element['only_alpha']) && $element['only_alpha'] === true) {
    $defaults['validators']['Alpha'] = '';
}

if(isset($element['only_digits']) && $element['only_digits'] === true) {
    $defaults['validators']['Digits'] = '';
}

if(isset($element['identical_to'])) {
    $defaults['validators']['Identical'] = '';
}

reverse_merge($this->elements[$name], $defaults);

?>
