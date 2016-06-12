<?php

$defaults = array(
    'id'                 => $name,
    'checked_value'      => '1',
    'unchecked_value'    => '0',
    'use_hidden_element' => true,
    'required'           => true,
    'validators'         => array('CheckboxChecked' => ''),
);

reverse_merge($this->elements[$name], $defaults);

?>
