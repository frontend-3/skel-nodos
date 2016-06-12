<?php

$defaults = array(
    'required'   => true,
    'validators' => array(
        'UploadFile' => array(),
    ),
);

if(isset($element['count'])) {
   $defaults['validators']['Count'] = array(
       'options' => array(
           'max' => $element['count'],
       ),
   );
}

if(isset($element['size'])) {
   $defaults['validators']['Size'] = array(
       'options' => array(
           'max' => $element['size'],
       ),
   );
}

if(isset($element['extension'])) {
   $defaults['validators']['Extension'] = array(
       'options' => array(
           'extension' => $element['extension'],
       ),
   );
}

if(isset($element['image_size'])) {
   $defaults['validators']['ImageSize'] = array(
       'options' => array(
           'minWidth'  => (isset($element['image_size']['min_width']) ? $element['image_size']['min_width'] : NULL),
           'maxWidth'  => (isset($element['image_size']['max_width']) ? $element['image_size']['max_width'] : NULL),
           'minHeight' => (isset($element['image_size']['min_height']) ? $element['image_size']['min_height'] : NULL),
           'maxHeight' => (isset($element['image_size']['max_height']) ? $element['image_size']['max_height'] : NULL),
       ),
   );
}

if(isset($element['is_image'])) {
   $defaults['validators']['IsImage'] = array();
}

reverse_merge($this->elements[$name], $defaults);

?>
