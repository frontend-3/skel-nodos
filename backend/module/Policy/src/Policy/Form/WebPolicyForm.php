<?php

namespace Policy\Form;

use Zend\Form\Element;
use Zend\Form\Form;

class WebPolicyForm extends Form{
    public function __construct(){
        parent::__construct('Policy');
        $this->setAttributes(array(
            'method' => 'post',
            'id'=>'filter_form'
        ));

        $e = new Element\Csrf('csrf_token');
        $e->setAttributes(array(
            'id' => 'csrf_token'
        ));
        $e->setOptions(array(
            'csrf_options' => array(
                'timeout' => 3600
            )
        ));
        $this->add($e);

        $e=new Element\Hidden('id');
        $this->add($e);

        $e = neW Element\Text('title');
        $e->setLabel('Título');
        $e->setAttributes(array(
            'size' => '30',
            'id' => 'code',
            'placeholder' => '',
            'maxlength' => 100
        ));
        $this->add($e);

        $e = neW Element\Textarea('content');
        $e->setLabel('Contenido');
        $e->setAttributes(array(
            'rows' => 10,
            'cols' => 80,
            'class' => 'description',
            'id' => 'description',
            'placeholder' => '',
            'readonly' => false,
        ));
        $this->add($e);


        $e = new Element\Submit('form-btn');
        $e->setAttributes(array(
            'id' => 'submit',
            'type' => 'submit',
            'class' => 'btn btn-primary',
            'value'=>'Grabar'
        ));
        $this->add($e);
    }
}