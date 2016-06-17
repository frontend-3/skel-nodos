<?php

$validators[] = array(
    'name'                   => 'File/IsImage',
    'break_chain_on_failure' => (isset($validator['break_chain_on_failure']) ? $validator['break_chain_on_failure'] : true),
    'options'                => $options,
);

?>
