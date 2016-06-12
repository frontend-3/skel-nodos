<?php

if(isset($element['empty_message'])) {
    $msg = $element['empty_message'];
}
else {
    $msg = 'Debes ingresar un valor';
}

$options_defaults = array(
    'messages' => array(
        'isEmpty' => $msg,
    ),
);

reverse_merge($options, $options_defaults);

$validators[] = array(
    'name'                   => 'NotEmpty',
    'break_chain_on_failure' => (isset($validator['break_chain_on_failure']) ? $validator['break_chain_on_failure'] : true),
    'options'                => $options,
);

?>
