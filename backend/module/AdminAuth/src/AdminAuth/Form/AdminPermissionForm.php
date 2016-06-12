<?php

namespace AdminAuth\Form;

use Zend\Form\Form;
use Zend\Form\Element;


class AdminPermissionForm extends Form
{
    public function __construct($name = null) 
    {
        parent::__construct('WebAuthPermissions');

        $this->setAttributes(array(
            'method' => 'post',
            'id' => 'filter_form'
        ));
        $e = new Element\Hidden('id');
        $this->add($e);

        $e = new Element\Csrf('csrf_token');
        $e->setAttributes(array('id' => 'csrf_token'));
        $e->setOptions(array(
            'csrf_options' => array(
                'timeout' => 3600
            )
        ));
        $this->add($e);

        $e = new Element\Text('name');
        $e->setLabel('Name');
        $e->setOptions(array(
            'type' => 'text',
            'id' => 'name',
            'placeholder' => '',
        ));
        $this->add($e);

        $e = new Element\Text('label');
        $e->setLabel('Etiqueta');
        $e->setOptions(array(
            'type' => 'text',
            'id' => 'name',
            'placeholder' => '',
        ));
        $this->add($e);

        $e = new Element\Select('status');
        $e->setLabel('Status');
        $e->setValueOptions(array(
            '1' => 'Activo',
            '0' => 'Inactivo',
        ));
        $this->add($e);

        $e = new Element\Submit('form-btn');
        $e->setAttributes(array(
            'id' => 'submit',
            'type' => 'submit',
            'class' => 'btn btn-primary',
            'value' => 'Grabar'
        ));
        $this->add($e);
    }

}
