<?php
// We'll now try to instantiate a stock ZF2 Form Element object
// It is expected to exist on
// vendor/zendframework/zendframework/library/Zend/Form/
$type  = $element['type'];
$class = '\\Zend\\Form\\Element\\' . $element['type'];
$class_file = 'vendor/zendframework/zendframework/library/Zend/Form/Element/' . $type . '.php';

if(!file_exists($class_file)) {
    throw new \Exception('Florence: Element type "' . $type . '" doesn\'t exist in form definition "' . $this->name . '" for controller "' . $this->module_name . '\\' . $this->controller_name . '" not found! Please check you\'ve typed it correctly.');
}

$e = new $class($name);

if(isset($element['id'])) {
   $e->setAttribute('id', $element['id']);
}

if(isset($element['required']) && $element['required'] === true) {
    $e->setAttribute('required', true);
}
else {
    $e->setAttribute('required', false);
}

if(isset($element['max_length'])) {
   $e->setAttribute('maxlength', $element['max_length']);
}

if(isset($element['size'])) {
   $e->setAttribute('size', $element['size']);
}

if(isset($element['class'])) {
   $e->setAttribute('class', $element['class']);
}

if(isset($element['label'])) {
    $e->setLabel($element['label']);
}

if(isset($element['options'])) {
    $e->setOptions($element['options']);
}

if(isset($element['valueOptions'])) {
    $e->setValueOptions($element['valueOptions']);
}

$this->form->add($e);

?>
