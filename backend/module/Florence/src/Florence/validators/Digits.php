<?php

$options_defaults = array(
);

reverse_merge($options, $options_defaults);

$validators[] = array(
    'name'                   => 'Digits',
    'break_chain_on_failure' => (isset($validator['break_chain_on_failure']) ? $validator['break_chain_on_failure'] : true),
    'options'                => $options,
);

?>
