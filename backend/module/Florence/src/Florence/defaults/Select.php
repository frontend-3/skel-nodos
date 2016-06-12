<?php

$defaults = array(
    'id'           => $name,
    'table_key'    => 'id',
    'table_value'  => 'name',
    'autoload'     => true,
    'multiple'     => false,
    'empty_option' => 'Selecciona',
    'options'      => array(),
);

reverse_merge($this->elements[$name], $defaults);

if(isset($element['model'])) {
    $this->elements[$name]['validators']['DbRecordExists'] = '';
    $this->elements[$name]['model_obj'] = $this->controller->model($element['model']);
    
    if($this->elements[$name]['autoload'] !== false) {
        $this->load_options_for($name);
    }
}

?>
