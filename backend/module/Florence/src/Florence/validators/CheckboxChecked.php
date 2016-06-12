<?php

$options_defaults = array(
    'checked_value' => $element['checked_value'],
    'required'      => $element['required'],
);

reverse_merge($options, $options_defaults);

$validators[] = array(
    'name'                   => 'Base\InputFilter\CheckboxChecked',
    'break_chain_on_failure' => (isset($validator['break_chain_on_failure']) ? $validator['break_chain_on_failure'] : true),
    'options'                => $options,
);

?>
