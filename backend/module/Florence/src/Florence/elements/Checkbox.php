<?php

$e = new \Zend\Form\Element\Checkbox($name);

if(isset($element['id'])) {
   $e->setAttribute('id', $element['id']);
}

if(isset($element['required']) && $element['required'] === true) {
    $e->setAttribute('required', 'true');
}

if(isset($element['use_hidden_element'])) {
    $e->setUseHiddenElement($element['use_hidden_element']);
}
else {
    $e->setUseHiddenElement(true);
}

$attrs = array(
    'label',
    'checked_value',
    'unchecked_value',
);
foreach($attrs as $a) {
    if(isset($element[$a])) {
        $options[$a] = $element[$a];
    }
}

$e->setOptions($options);

$e->setChecked((isset($element['checked']) ? $element['checked'] : false));

$this->form->add($e);

?>
