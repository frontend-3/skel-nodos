<?php

namespace AdminAuth\Form;

use Zend\Form\Form;
use Zend\Form\Element;


class AdminUserForm extends Form
{
    public function __construct($values) 
    {
        parent::__construct('WebAuthUser');

        $this->setAttributes(array(
            'method' => 'post',
            'id' => 'filter_form'
        ));

        $e = new Element\Csrf('csrf_token');
        $e->setAttributes(array(
            'id' => 'csrf_token'
        ));
        $e->setOptions(array(
            'csrf_options' => array('timeout' => 3600)
        ));
        $this->add($e);

        $e = new Element\Hidden('id');
        $this->add($e);

        $e = new Element\Text('first_name');
        $e->setLabel('Nombre');
        $e->setOptions(array(
            'type' => 'text',
            'id' => 'first_name',
            'placeholder' => '',
            'readonly' => false,
            'maxlength' => 45,
        ));
        $this->add($e);

        $e = new Element\Text('last_name');
        $e->setLabel('Apellido');
        $e->setOptions(array(
            'type' => 'text',
            'id' => 'last_name',
            'placeholder' => '',
            'readonly' => false,
            'maxlength' => 45,
        ));
        $this->add($e);

        $e = new Element\Email('email');
        $e->setLabel('Email');
        $e->setOptions(array(
            'type' => 'text',
            'id' => 'email',
            'placeholder' => '',
            'readonly' => false,
            'maxlength' => 100,
        ));
        $this->add($e);

        $e = new Element\Password('password');
        $e->setLabel('Password');
        $e->setOptions(array(
            'type' => 'text',
            'id' => 'password',
            'placeholder' => '',
            'readonly' => false,
            'maxlength' => 100,
        ));
        $this->add($e);

        $e = new Element\Select('role_id');
        $e->setLabel('Rol');
        $e->setValueOptions($values);
        $e->setLabel('Role');
        $this->add($e);

        $e = new Element\Select('status');
        $e->setLabel('Estado');
        $e->setValueOptions(array(
            'enabled' => 'Activo',
            'disabled' => 'Inactivo',
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