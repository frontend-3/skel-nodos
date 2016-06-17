<?php

namespace AdminAuth\Form;

use Zend\Form\Form;
use Zend\Form\Element;


class AdminRoleForm extends Form
{
    public function __construct($values=null)
    {
        parent::__construct('WebAuthForm');

        $this->setAttributes(array(
            'method' => 'post',
            'id' => 'filter_form'
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

        $hidden = new Element\Hidden('id');
        $this->add($hidden);

        $e = new Element\Text('name');
        $e->setLabel('Name');
        $e->setOptions(array(
            'type' => 'text',
            'id' => 'name',
            'placeholder' => '',
            'readonly' => false,
            'maxlength' => 45,
        ));
        $this->add($e);

        $e = new Element\MultiCheckbox('permissions');
        $e->setLabel('Permissions');
        $e->setLabelAttributes(array('class' => 'col-md-4'));
        if(!is_null($values)){
            $e->setValueOptions($values);
        }
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