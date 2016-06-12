<?php

/**
 * Created by PhpStorm.
 * User: Flash
 * Date: 19/06/14
 * Time: 10:01 AM
 */

namespace Site\Form\User;

use Zend\Form\Form;
use Zend\Form\Element;

class WebUserProfileForm extends Form {

    private function getDays() {
        $days = array();
        $days[''] = 'DÏA';
        for ($i = 1; $i <= 31; $i++) {
            $index = str_pad($i, 2, '0', STR_PAD_LEFT);
            $days[$index] = $index;
        }
        return $days;
    }

    private function getMonths() {
        $names_month = array(
            "ENERO", 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO',
            'JULIO', 'AGOSTO', 'SETIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE');
        $months = array();
        $index = 1;
        $months[''] = 'MES';
        foreach ($names_month as $name) {
            $months[str_pad($index, 2, '0', STR_PAD_LEFT)] = $name;
            $index++;
        }
        return $months;
    }

    private function getYear() {
        $years = array();
        $years[''] = 'AÑO';
        for ($i = 1940; $i < 2000; $i++) {
            $years[$i] = $i;
        }
        return $years;
    }

    public function __construct($name = null) {
        parent::__construct('Users');
        $e = new Element\Text('first_name');
        $e->setAttribute('id', 'first_name');
        $this->add($e);

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

        $e = new Element\Text('last_name');
        $e->setAttribute('id', 'last_name');
        $this->add($e);

        $e = new Element\Text('telephone');
        $e->setAttribute('id', 'telephone');
        $this->add($e);

        $e = new Element\Text('cellphone');
        $e->setAttribute('id', 'cellphone');
        $this->add($e);

        $e = new Element\Text('document_number');
        $e->setAttribute('id', 'document_number');
        $this->add($e);

        $e = new Element\Select('cont_day');
        $e->setAttribute('id', 'cont_day');
        $e->setValueOptions($this->getDays());
        $this->add($e);

        $e = new Element\Select('cont_month');
        $e->setAttribute('id', 'cont_month');
        $e->setValueOptions($this->getMonths());
        $this->add($e);

        $e = new Element\Select('cont_year');
        $e->setAttribute('id', 'cont_year');
        $e->setValueOptions($this->getYear());
        $this->add($e);

        $e = new Element\Text('address');
        $e->setAttribute('id', 'address');
        $this->add($e);

        $e = new Element\Text('email');
        $e->setAttribute('id', 'email');
        $this->add($e);

        $e = new Element\Text('confirm_email');
        $e->setAttribute('id', 'confirm_email');
        $this->add($e);

        $e = new Element\Text('password');
        $e->setAttribute('id', 'password');
        $this->add($e);

        $e = new Element\Text('confirm_password');
        $e->setAttribute('id', 'confirm_password');
        $this->add($e);

        $e = new Element\Submit('register');
        $this->add($e);
    }

}
