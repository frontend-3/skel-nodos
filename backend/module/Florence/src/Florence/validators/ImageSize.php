<?php

$validators[] = array(
    'name'                   => 'File/ImageSize',
    'break_chain_on_failure' => (isset($validator['break_chain_on_failure']) ? $validator['break_chain_on_failure'] : true),
    'options'                => $options,
);

?>
