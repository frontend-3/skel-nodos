<?php

/**
 * Created by PhpStorm.
 * User: Flash
 * Date: 23/06/14
 * Time: 11:43 AM
 */

namespace User\InputFilter;

use Zend\Db\Adapter\Adapter;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;
use Zend\Validator\Digits;
use Zend\Validator\EmailAddress;
use Zend\Validator\Db\RecordExists;
use Zend\Validator\Db\NoRecordExists;
use Zend\Validator\Identical;
use Zend\I18n\Validator\Alpha;
use Zend\Validator\Date;

class WebUserRegisterInputFilter implements InputFilterAwareInterface {

    protected $dbAdapter;
    protected $inputFilter;

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
                'name' => 'first_name',
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
                            'max' => 150,
                            'messages' => array(
                                StringLength::TOO_SHORT => 'Ingresa como mínimo 1 caracter',
                                StringLength::TOO_LONG => 'No ingreses más de 50 caracteres'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'Alpha',
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
                        'options' => array(
                            'messages' => array(
                                NotEmpty::IS_EMPTY => 'Ingresa tu nombre'
                            ),
                        ),
                    ),
                ),
            ));

            $this->inputFilter->add(array(
                'name' => 'last_name',
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
                            'max' => 150,
                            'messages' => array(
                                StringLength::TOO_SHORT => 'Ingresa como mínimo 1 caracter',
                                StringLength::TOO_LONG => 'No ingreses más de 50 caracteres'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'Alpha',
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
                        'options' => array(
                            'messages' => array(
                                NotEmpty::IS_EMPTY => 'Ingresa tu apellido'
                            ),
                        ),
                    ),
                ),
            ));

            $this->inputFilter->add(array(
                'name' => 'document_number',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Digits',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'messages' => array(
                                Digits::NOT_DIGITS => 'Ingresa un DNI válido.',
                                Digits::STRING_EMPTY => 'Ingresa un DNI válido',
                                Digits::INVALID => 'Ingresa un DNI válido',
                            ),
                        ),
                    ),
                    array(
                        'name' => 'StringLength',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 8,
                            'max' => 8,
                            'messages' => array(
                                StringLength::TOO_SHORT => 'Ingresa como mínimo 8 caracteres',
                                StringLength::TOO_LONG => 'No ingreses más de 8 caracteres'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'messages' => array(
                                NotEmpty::IS_EMPTY => 'Ingresa tu dni'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'Zend\Validator\Db\NoRecordExists',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'table' => 'web_users',
                            'field' => 'document_number',
                            'adapter' => $this->dbAdapter,
                            'messages' => array(
                                NoRecordExists::ERROR_RECORD_FOUND => 'El DNI ya ha sido registrado'
                            ),
                        ),
                    ),
                ),
            ));


            $this->inputFilter->add(array(
                'name' => 'telephone',
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
                            'min' => 6,
                            'max' => 9,
                            'messages' => array(
                                StringLength::TOO_SHORT => 'Ingresa como mínimo 6 caracter',
                                StringLength::TOO_LONG => 'No ingreses más de 9 caracteres'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                NotEmpty::IS_EMPTY => 'Ingresa tu teléfono'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'Digits',
                        'options' => array(
                            'messages' => array(
                                Digits::INVALID => 'Solo ingresa números',
                                Digits::NOT_DIGITS => 'Solo ingresa números',
                            ),
                        ),
                    )
                ),
            ));


            $this->inputFilter->add(array(
                'name' => 'mobile_phone',
                'required' => false,
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
                            'min' => 7,
                            'max' => 9,
                            'messages' => array(
                                StringLength::TOO_SHORT => 'Ingresa como mínimo 7 caracter',
                                StringLength::TOO_LONG => 'No ingreses más de 9 caracteres'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                NotEmpty::IS_EMPTY => 'Ingresa tu celular'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'Digits',
                        'options' => array(
                            'messages' => array(
                                Digits::INVALID => 'Solo ingresa números',
                                Digits::NOT_DIGITS => 'Solo ingresa números',
                            ),
                        ),
                    )
                ),
            ));


            $this->inputFilter->add(array(
                'name' => 'birthday',
                'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                NotEmpty::IS_EMPTY => 'Ingresa tu celular'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'Date',
                        'options' => array(
                            'format'=>'d/m/Y',
                            'messages' => array(
                               Date::INVALID_DATE => 'Formato Inválido',
                               Date::INVALID=> 'Formato Inválido'
                            ),
                        ),
                    ),

                ),
            ));


            $this->inputFilter->add(array(
                'name' => 'address',
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
                            'max' => 150,
                            'messages' => array(
                                StringLength::TOO_SHORT => 'Ingresa como mínimo 1 caracter',
                                StringLength::TOO_LONG => 'No ingreses más de 50 caracteres'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'messages' => array(
                                NotEmpty::IS_EMPTY => 'Ingresa tu apellido'
                            ),
                        ),
                    ),
                ),
            ));


            $this->inputFilter->add(array(
                'name' => 'cod_dpto',
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
                                NotEmpty::IS_EMPTY => 'No has ingresado tu Departamento'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'Digits',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'messages' => array(
                                Digits::NOT_DIGITS => 'No has ingresado tu Departamento',
                                Digits::INVALID => 'No has ingresado tu Departamento',
                                Digits::STRING_EMPTY=>'No ha ingresado tu Departamento'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'Zend\Validator\Db\RecordExists',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'table' => 'web_ubigeo_departments',
                            'field' => 'id',
                            'adapter' => $this->dbAdapter,
                            'messages' => array(
                                RecordExists::ERROR_NO_RECORD_FOUND => 'Departamento no válido'
                            ),
                        ),
                    ),
                ),
            ));

            $this->inputFilter->add(array(
                'name' => 'cod_prov',
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
                                NotEmpty::IS_EMPTY => 'No has ingresado tu Provincia'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'Digits',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'messages' => array(
                                Digits::NOT_DIGITS => 'No has ingresado tu Provincia',
                                Digits::INVALID => 'No has ingresado tu Provincia',
                                Digits::STRING_EMPTY=>'No ha ingresado tu Provincia'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'Zend\Validator\Db\RecordExists',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'table' => 'web_ubigeo_provinces',
                            'field' => 'id',
                            'adapter' => $this->dbAdapter,
                            'messages' => array(
                                RecordExists::ERROR_NO_RECORD_FOUND => 'Provincia no válido'
                            ),
                        ),
                    ),
                ),
            ));

            $this->inputFilter->add(array(
                'name' => 'cod_dist',
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
                                NotEmpty::IS_EMPTY => 'No has ingresado tu Distrito'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'Digits',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'messages' => array(
                                Digits::NOT_DIGITS => 'No has ingresado tu Distrito',
                                Digits::INVALID => 'No has ingresado tu Distrito',
                                Digits::STRING_EMPTY=>'No ha ingresado tu Distrito'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'Zend\Validator\Db\RecordExists',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'table' => 'web_ubigeo_districts',
                            'field' => 'id',
                            'adapter' => $this->dbAdapter,
                            'messages' => array(
                                RecordExists::ERROR_NO_RECORD_FOUND => 'Distrito no válido'
                            ),
                        ),
                    ),
                ),
            ));

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
                                StringLength::TOO_SHORT => 'Ingresa como mínimo 1 caracter',
                                StringLength::TOO_LONG => 'No ingreses más de 200 caracteres'
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
                    array(
                        'name' => 'Zend\Validator\Db\NoRecordExists',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'table' => 'web_users',
                            'field' => 'email',
                            'adapter' => $this->dbAdapter,
                            'messages' => array(
                                NoRecordExists::ERROR_RECORD_FOUND => 'El email ya ha sido registrado'
                            ),
                        ),
                    )
                ),
            ));

            /*$this->inputFilter->add(array(
                'name' => 'confirm_email',
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
                                StringLength::TOO_SHORT => 'Ingresa como mínimo 1 caracter',
                                StringLength::TOO_LONG => 'No ingreses más de 200 caracteres'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'messages' => array(
                                NotEmpty::IS_EMPTY => 'Confirma tu email'
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
                    array(
                        'name' => 'Zend\Validator\Identical',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'token' => 'email',
                            'messages' => array(
                                Identical::NOT_SAME => 'Confirma tu email'
                            ),
                        ),
                    ),
                ),
            ));*/

            $this->inputFilter->add(array(
                'name' => 'password',
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
                            'min' => 4,
                            'max' => 20,
                            'messages' => array(
                                StringLength::TOO_SHORT => 'Ingresa como mínimo 4 caracteres',
                                StringLength::TOO_LONG => 'No ingreses más de 20 caracteres'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'messages' => array(
                                NotEmpty::IS_EMPTY => 'Ingresa tu contraseña'
                            ),
                        ),
                    ),
                    /*array(
                        'break_chain_on_failure' => true,
                        'name' => 'Model\User\InputFilter\StrengthPassword'
                    )*/
                ),
            ));

            $this->inputFilter->add(array(
                'name' => 'confirm_password',
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
                            'min' => 4,
                            'max' => 20,
                            'messages' => array(
                                StringLength::TOO_SHORT => 'Ingresa como mínimo 4 caracteres',
                                StringLength::TOO_LONG => 'No ingreses más de 20 caracteres'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'messages' => array(
                                NotEmpty::IS_EMPTY => 'Confirma tu contraseña'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'Zend\Validator\Identical',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'token' => 'password',
                            'messages' => array(
                                Identical::NOT_SAME => 'Confirma tu password'
                            ),
                        ),
                    ),
                    /*array(
                        'break_chain_on_failure' => true,
                        'name' => 'Model\User\InputFilter\StrengthPassword'
                    )*/
                ),
            ));

           /* $this->inputFilter->add(array(
                'name' => 'tyc',
                'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Digits',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'messages' => array(
                                Digits::NOT_DIGITS => 'No has aceptado los tyc',
                                Digits::INVALID => 'No has aceptado los tyc',
                                Digits::STRING_EMPTY=>'No has aceptado los tyc'
                            ),
                        ),
                    ),
                ),
            ));*/

            $this->inputFilter->add(array(
                'name' => 'suscribe',
                'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Digits',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'messages' => array(
                                Digits::NOT_DIGITS => 'No has aceptado los suscribe',
                                Digits::INVALID => 'No has aceptado los suscribe',
                                Digits::STRING_EMPTY=>'No has aceptado los suscribe'
                            ),
                        ),
                    ),
                ),
            ));
        }
        return $this->inputFilter;
    }

}
