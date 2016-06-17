<?php

$defaults = array(
    'id'       => $name,
    'required' => true,
);

reverse_merge($this->elements[$name], $defaults);

?>
