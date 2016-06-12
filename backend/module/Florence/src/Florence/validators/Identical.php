<?php

$options_defaults = array(
);

reverse_merge($options, $options_defaults);

if(isset($element['identical_to'])) {
   $options['token'] = $element['identical_to'];
}

$validators[] = array(
    'name'                   => 'Identical',
    'break_chain_on_failure' => (isset($validator['break_chain_on_failure']) ? $validator['break_chain_on_failure'] : true),
    'options'                => $options,
);

?>
