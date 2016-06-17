<?php

$defaults = array(
    'id'                 => $name,
    'use_hidden_element' => true,
    'required'           => false,
);

reverse_merge($this->elements[$name], $defaults);

?>
