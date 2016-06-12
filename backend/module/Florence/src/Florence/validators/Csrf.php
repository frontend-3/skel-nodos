<?php

$options['timeout'] = $element['timeout'];

$validators[] = array(
    'name'                   => 'Csrf',
    'break_chain_on_failure' => (isset($validator['break_chain_on_failure']) ? $validator['break_chain_on_failure'] : true),
    'options'                => $options,
);

?>
