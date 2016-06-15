<?php
$captcha_image = new \Zend\Captcha\Image($element['image']);
$captcha_image->setExpiration($element['image_expiration']);
$captcha_image->setGcFreq($element['image_gcfreq']);

$e = new \Zend\Form\Element\Captcha($name);
$e->setAttribute('id', $element['id']);
$e->setOptions(array('captcha' => $captcha_image));

$this->form->add($e);

?>
