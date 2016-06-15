<?php
// Build defaults
$options_defaults = array(
    'encoding' => 'UTF-8',
);

if(isset($element['min_length']) || isset($element['max_length'])) {
    $min     = (isset($element['min_length']) ? $element['min_length'] : NULL);
    $min_msg = (isset($element['min_length_message']) ? $element['min_length_message'] : 'No debe contener menos de ' . $min . ' caracteres');
    
    $max     = (isset($element['max_length']) ? $element['max_length'] : NULL);
    $max_msg = (isset($element['max_length_message']) ? $element['max_length_message'] : 'No debe contener más de ' . $max . ' caracteres');
}
    
if($min !== NULL) {
   $options_defaults['min'] = $min;
//   $options_defaults['messages']['stringLengthTooShort'] = $min_msg;
}

if($max !== NULL) {
   $options_defaults['max'] = $max;
//   $options_defaults['messages']['stringLengthTooLong'] = $max_msg;
}

// If on the YAML definition file one puts min_length: X and a different
// 'min' attribute for the StringLength validation, the min_length
// trumps by default. This is in order to avoid confusion on conflicting
// values. Same goes for max/max_length.
if(isset($options['min'])) {
    $options['min'] = $element['max_length'];
}
if(isset($options['max'])) {
    $options['max'] = $element['max_length'];
}

reverse_merge($options, $options_defaults);

$validators[] = array(
    'name'                   => 'StringLength',
    'break_chain_on_failure' => (isset($validator['break_chain_on_failure']) ? $validator['break_chain_on_failure'] : true),
    'options'                => $options,
);

?>
