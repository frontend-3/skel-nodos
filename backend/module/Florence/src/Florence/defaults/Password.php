<?php

$defaults = array(
    'id'       => $name,
    'required' => true,
);

if(isset($element['min_length']) || isset($element['max_length'])) {
    $defaults['validators']['StringLength'] = '';
}

if(isset($element['identical_to'])) {
    $defaults['validators']['Identical'] = '';
}

reverse_merge($this->elements[$name], $defaults);

?>
