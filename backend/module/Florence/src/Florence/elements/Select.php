<?php
$e = new \Zend\Form\Element\Select($name);

if(isset($element['id'])) {
   $e->setAttribute('id', $element['id']);
}

if(isset($element['required']) && $element['required'] === true) {
    $e->setAttribute('required', 'true');
}

if(isset($element['label'])) {
    $e->setLabel($element['label']);
}

if($element['empty_option'] !== false) {
    $element['options']['empty_option'] = $element['empty_option'];
}

$e->setOptions($element['options']);

if(isset($element['valueOptions'])) {
    $e->setValueOptions($element['valueOptions']);
}

if(isset($element['size'])) {
    $e->setAttribute('size', $element['size']);
}

if($element['multiple']) {
    $e->setAttribute('multiple', 'true');
}

$this->form->add($e);

?>
