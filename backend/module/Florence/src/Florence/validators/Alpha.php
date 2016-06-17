<?php

$options_defaults = array(
    'allowWhitespace' => true,
);

reverse_merge($options, $options_defaults);

$validators[] = array(
    'name'                   => 'Alpha',
    'break_chain_on_failure' => (isset($validator['break_chain_on_failure']) ? $validator['break_chain_on_failure'] : true),
    'options'                => $options,
);

?>
