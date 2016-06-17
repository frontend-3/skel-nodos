<?php

namespace Seo\Form;

use Zend\Form\Element;
use Zend\Form\Form;

class WebSeoForm extends Form{
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

        $e = neW Element\Text('route');
        $e->setLabel('Ruta');
        $e->setAttributes(array(
            'size' => '30',
            'id' => 'code',
            'placeholder' => '',
            'maxlength' => 100
        ));
        $this->add($e);

        $e = new Element\Text('title');
        $e->setLabel('Nombre de la Página');
        $e->setAttributes(array(
            'type' => 'text',
            'id' => 'name',
            'placeholder' => '',
            'readonly' => false,
            'maxlength' => 45,
            'size' => 30
        ));
        $this->add($e);

        $e = new Element\Text('seo_title');
        $e->setLabel('SEO Título');
        $e->setAttributes(array(
            'type' => 'text',
            'id' => 'name',
            'placeholder' => '',
            'readonly' => false,
            'maxlength' => 70,
            'class' => 'counter',
        ));
        $this->add($e);

        $e = new Element\Select('seo_noindex');
        $e->setLabel('SEO Index');
        $e->setValueOptions(array(
            0 => 'Activo',
            '1' => 'Inactivo',
        ));
        $this->add($e);

        $e = new Element\Textarea('seo_description');
        $e->setLabel('SEO Descripción');
        $e->setAttributes(array(
            'id' => 'description',
            'placeholder' => '',
            'readonly' => false,
            'class' => 'counter',
            'rows' => 10,
            'cols' => 80,
            'maxlength' => 160
        ));
        $this->add($e);

        $e = new Element\Textarea('seo_keywords');
        $e->setLabel('SEO Keywords');
        $e->setAttributes(array(
            'id' => 'description',
            'placeholder' => '',
            'readonly' => false,
            'rows' => 10,
            'cols' => 80
        ));
        $this->add($e);

        $e = new Element\File('seo-image');
        $e->setLabel('SEO Imagen');
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