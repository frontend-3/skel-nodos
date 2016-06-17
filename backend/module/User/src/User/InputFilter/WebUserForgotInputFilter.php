<?php

/**
 * Created by PhpStorm.
 * User: Flash
 * Date: 27/06/14
 * Time: 02:35 PM
 */

namespace User\InputFilter;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\Db\RecordExists;
use Zend\Validator\EmailAddress;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;
use Zend\Db\Adapter\Adapter;

class WebUserForgotInputFilter implements InputFilterAwareInterface {

    protected $inputFilter;
    protected $dbAdapter;

    public function __construct(Adapter $dbAdapter) {
        $this->dbAdapter = $dbAdapter;
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {

            $this->inputFilter = new InputFilter();
            $this->inputFilter->add(array(
                'name' => 'email',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 200,
                            'messages' => array(
                                StringLength::TOO_SHORT => 'Ingrese un correo válido',
                                StringLength::TOO_LONG => 'Ingrese un correo válido'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'messages' => array(
                                NotEmpty::IS_EMPTY => 'Ingrese un correo válido'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'EmailAddress',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'messages' => array(
                                EmailAddress::INVALID_FORMAT => 'Ingrese un correo válido',
                                EmailAddress::INVALID_HOSTNAME=>'Ingrese un correo válido',
                            ),
                        ),
                    ),
                    array(
                        'name' => 'Zend\Validator\Db\RecordExists',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'table' => 'web_users',
                            'field' => 'email',
                            'adapter' => $this->dbAdapter,
                            'messages' => array(
                                RecordExists::ERROR_NO_RECORD_FOUND => ''
                            ),
                        ),
                    ),
                ),
            ));
        }
        return $this->inputFilter;
    }

}
