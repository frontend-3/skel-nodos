<?php

$defaults = array(
    'id'         => $name,
    'required'   => true,
    'timeout'    => 3600,
    'validators' => array(
        'Csrf' => '',
    ),
);

reverse_merge($this->elements[$name], $defaults);

?>
