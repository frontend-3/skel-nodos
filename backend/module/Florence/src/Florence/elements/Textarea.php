<?php

$e = new \Zend\Form\Element\Textarea($name);

if(isset($element['id'])) {
   $e->setAttribute('id', $element['id']);
}

if(isset($element['required']) && $element['required'] === true) {
    $e->setAttribute('required', 'true');
}

$attrs = array(
    'cols',
    'rows',
    'class',
);

foreach($attrs as $a) {
    if(isset($element[$a])) {
        $e->setAttribute($a, $element[$a]);
    }
}

$opts = array(
    'label',
    'checked_value',
    'unchecked_value',
);

if(isset($element['options'])) {
    $options = $element['options'];
}
else {
    $options = array();
}

foreach($opts as $a) {
    if(isset($element[$a])) {
        $options[$a] = $element[$a];
    }
}

$e->setOptions($options);

$this->form->add($e);

?>
