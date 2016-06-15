<?php

/**
 * Created by PhpStorm.
 * User: Flash
 * Date: 19/06/14
 * Time: 10:01 AM
 */

namespace User\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class WebUserForm extends Form {

    private function getDays() {
        $days = array();
        $days[''] = 'DÍA';
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
        for ($i = 1940; $i < 1997; $i++) {
            $years[$i] = $i;
        }
        return $years;
    }

    public function __construct($deparment = null, $provinces = null, $districts = null) {
        parent::__construct('Users');
        $this->setAttribute('autocomplete','off');
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

        $e = new Element\Text('document_number');
        $e->setAttribute('id', 'document_number');
        $this->add($e);

        $e = new Element\Text('document_number');
        $e->setAttribute('id', 'document_number');
        $this->add($e);

        $e = new Element\Text('telephone');
        $e->setAttribute('id', 'telephone');
        $this->add($e);

        $e = new Element\Text('mobile_phone');
        $e->setAttribute('id', 'mobile_phone');
        $this->add($e);

        $e = new Element\Text('birthday');
        $e->setAttribute('id', 'birthday');
        $this->add($e);

        $e = new Element\Text('address');
        $e->setAttribute('id', 'address');
        $this->add($e);

        $e = new Element\Select('cod_dpto');
        if(!is_null($deparment)){
            $e->setValueOptions($deparment);
        }
        $e->setAttribute('id', 'cod_dpto');
        $this->add($e);

        $e = new Element\Select('cod_prov');
        if(!is_null($provinces)){
            $e->setValueOptions($provinces);
        }
        $e->setAttribute('id', 'cod_prov');
        $this->add($e);

        $e = new Element\Select('cod_dist');
        if(!is_null($districts)){
            $e->setValueOptions($districts);
        }
        $e->setAttribute('id', 'cod_dist');
        $this->add($e);

        $e = new Element\Text('email');
        $e->setAttribute('id', 'email');
        $this->add($e);

        $e = new Element\Password('password');
        $e->setAttribute('id', 'password');
        $this->add($e);

        $e = new Element\Password('confirm_password');
        $e->setAttribute('id', 'confirm_password');
        $this->add($e);

        $e = new Element\Checkbox('tyc');
        $e->setAttribute('id', 'tyc');
        $e->setOptions(array(
            'checked_value' => '1',
            'unchecked_value' => 'no',
            'disable_inarray_validator' => false
        ));
        $this->add($e);

        $e = new Element\Checkbox('suscribe');
        $e->setAttribute('id', 'suscribe');
        $e->setOptions(array(
            'checked_value' => '1',
            'unchecked_value' => '0',
            'disable_inarray_validator' => false
        ));
        $this->add($e);
        $e = new Element\Submit('form-btn');
        $e->setValue('Registrar');
        $this->add($e);
    }

}
