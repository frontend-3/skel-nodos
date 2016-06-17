<?php

namespace Contact\InputFilter;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;
use Zend\Validator\Digits;
use Zend\Validator\EmailAddress;
use Zend\Db\Adapter\Adapter;
use Zend\Validator\Db\RecordExists;
use Zend\I18n\Validator\Alpha;
use Zend\I18n\Validator\Alnum;

class WebContactInputFilter implements InputFilterAwareInterface {

    protected $inputFilter;
    protected $dbAdapter;

    public function __construct(Adapter $dbAdapter){
        $this->dbAdapter = $dbAdapter;
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'full_name',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 100,
                            'messages' => array(
                                StringLength::TOO_SHORT => 'Ingresa como mínimo 1 caracter',
                                StringLength::TOO_LONG => 'No ingreses más de 100 caracteres'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'Alpha',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'allowWhiteSpace'=>true,
                            'messages' => array(
                                Alpha::INVALID => 'Solo ingresa letras',
                                Alpha::NOT_ALPHA => 'Solo ingresa letras',
                            ),
                        ),
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'messages' => array(
                                NotEmpty::IS_EMPTY => 'Ingresa tu Nombre completo'
                            ),
                        ),
                    ),
                ),
            ));


            $inputFilter->add(array(
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
                            'max' => 75,
                            'messages' => array(
                                StringLength::TOO_SHORT => 'Ingresa como mínimo 1 caracter',
                                StringLength::TOO_LONG => 'No ingreses más de 75 caracteres'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'messages' => array(
                                NotEmpty::IS_EMPTY => 'Ingresa tu email'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'EmailAddress',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'messages' => array(
                                EmailAddress::INVALID_FORMAT => 'Ingresa un Email válido',
                                EmailAddress::INVALID_HOSTNAME=>'Ingrese un Email válido',
                            ),
                        ),
                    ),
                ),
            ));


           /* $inputFilter->add(array(
                'name' => 'category_id',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Digits',
                        'options' => array(
                            'messages' => array(
                                Digits::NOT_DIGITS => 'Ingresa un asunto válido',
                                Digits::STRING_EMPTY => 'Ingresa un asunto válido',
                                Digits::INVALID => 'Ingresa un asunto válido',
                            ),
                        ),
                    ),
                    array(
                        'name' => 'Zend\Validator\Db\RecordExists',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'table' => 'web_contact_categories',
                            'field' => 'id',
                            'adapter' => $this->dbAdapter,
                            'messages' => array(
                                RecordExists::ERROR_NO_RECORD_FOUND => 'Ingresa un asunto válido'
                            ),
                        ),
                    ),
                ),
            ));*/


            $inputFilter->add(array(
                'name' => 'comment',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'messages' => array(
                                NotEmpty::IS_EMPTY => 'Ingresa tu comentario'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'Alnum',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'allowWhiteSpace'=>true,
                            'messages' => array(
                                Alnum::INVALID => 'Solo ingresa letras y números',
                                Alnum::NOT_ALNUM => 'Solo ingresa letras y números',
                            ),
                        ),
                    ),
                    array(
                        'name' => 'StringLength',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 5000,
                            'messages' => array(
                                StringLength::TOO_SHORT => 'Ingresa como mínimo 1 caracter',
                                StringLength::TOO_LONG => 'No ingreses más de 5000 caracteres'
                            ),
                        ),
                    ),

                ),
            ));
            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

}
