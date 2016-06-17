<?php

$options_defaults = array(
    'field'   => 'id',
    'adapter' => $this->controller->getDBAdapter(),
);

reverse_merge($options, $options_defaults);

// From the element itself
if(isset($element['table_key'])) {
    $options['field'] = $element['table_key'];
}

if(isset($element['model'])) {
    $options['table'] = $element['model_obj']->getTableName();
}

$validators[] = array(
    'name'                   => 'Zend\Validator\Db\RecordExists',
    'break_chain_on_failure' => (isset($validator['break_chain_on_failure']) ? $validator['break_chain_on_failure'] : true),
    'options'                => $options,
);

?>
