<?php

$defaults = array(
    'id'       => $name,
    'required' => true,
    'filters'  => array(
        'StripTags',
        'StringTrim',
    ),
);

reverse_merge($this->elements[$name], $defaults);

?>
