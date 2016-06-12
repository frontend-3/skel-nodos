<?php

$e = new \Zend\Form\Element\MultiCheckbox($name);

if(isset($element['id'])) {
   $e->setAttribute('id', $element['id']);
}

if(isset($element['use_hidden_element'])) {
    $e->setUseHiddenElement($element['use_hidden_element']);
}
else {
    $e->setUseHiddenElement(true);
}

$options = array();
$attrs = array(
    'label',
    'selected',
    'disabled',
    'value',
);
foreach($attrs as $a) {
    if(isset($element[$a])) {
        $options[$a] = $element[$a];
    }
}
$e->setOptions($options);

$e->setValueOptions($element['valueOptions']);

$this->form->add($e);

?>
