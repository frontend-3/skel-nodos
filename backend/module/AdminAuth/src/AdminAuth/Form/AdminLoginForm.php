<?php

namespace AdminAuth\Form;

use Zend\Form\Form;
use Zend\Form\Element;


class AdminLoginForm extends Form {
    public function __construct($name = null) {
        parent::__construct('login');

        $this->setAttribute('method', 'post');
        $this->setAttribute('autocomplete','off');
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
        $username = new Element\Text('username');
        $username->setLabel('Username')
            ->setAttribute('size', '50')
            ->setAttribute('placeholder', 'Usuario')
            ->setAttribute('class', 'form-control')
            ->setAttribute('required', true);
        $this->add($username);
        $password = new Element\Password('password');
        $password->setLabel('Password')
            ->setAttribute('size', '50')
            ->setAttribute('placeholder', 'Usuario')
            ->setAttribute('class', 'form-control')
            ->setAttribute('required', true);
        $this->add($password);

        $csrf = new Element\Csrf('csrf');
        $this->add($csrf);

        $submit = new Element\Submit('submit');
        $submit->setValue('Log In');
        $submit->setAttributes(array(
            'type' => 'submit',
            'class' => 'btn btn-primary btn-cons m-t-10',
        ));
        $this->add($submit);
    }
}