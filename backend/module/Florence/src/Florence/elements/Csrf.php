<?php

$options = array(
    'timeout' => 3600,
);

reverse_merge($this->elements[$name]['csrf_options'], $options);

$e = new \Zend\Form\Element\Csrf($name);
if(isset($element['id'])) {
    $e->setAttribute('id', $element['id']);
}

$this->form->add($e);

?>
