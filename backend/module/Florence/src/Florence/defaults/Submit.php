<?php

$defaults = array(
    'id'       => $name,
    'required' => false,
);

reverse_merge($this->elements[$name], $defaults);

?>
