<?php

namespace AdminAuth\InputFilter;

use Zend\Db\Adapter\Adapter;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

use Zend\Validator\Db\NoRecordExists;
use Zend\Validator\EmailAddress;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;


class AdminUserInputFilter implements InputFilterAwareInterface {

    public $exclude_id;
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
            $inputFilter = new InputFilter();
            $inputFilter->add(array(
                'name' => 'first_name',
                'required' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                NotEmpty::IS_EMPTY => 'Ingresa el nombre'
                            ),
                        ),
                    ),
                )
            ));

            $inputFilter->add(array(
                'name' => 'last_name',
                'required' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                NotEmpty::IS_EMPTY => 'Ingresa el apellido'
                            ),
                        ),
                    ),
                )
            ));
            $validators = array(
                array(
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            NotEmpty::IS_EMPTY => 'Ingresa el email'
                        ),
                    ),
                ),
                array(
                    'name' => 'EmailAddress',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            EmailAddress::INVALID_FORMAT => 'Ingresa un Email válido'
                        ),
                    ),
                ),
            );
            if($this->exclude_id>0){
                $validators[] = array(
                    'name' => 'Zend\Validator\Db\NoRecordExists',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'table' => 'web_auth_user',
                        'field' => 'email',
                        'adapter' => $this->dbAdapter,
                        'exclude' => array(
                            'field' => 'id',
                            'value' => $this->exclude_id
                        ),
                        'messages' => array(
                            NoRecordExists::ERROR_RECORD_FOUND => 'El email ya ha sido registrado'
                        ),
                    ),
                );
            }
            $filters = array(
                'name' => 'email',
                'required' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => $validators
            );
            $inputFilter->add($filters);

            $inputFilter->add(array(
                'name' => 'password',
                'required' => false,
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'min' => 6,
                            'messages' => array(
                                StringLength::TOO_SHORT => 'Ingresa como mínimo 4 caracter'
                            ),
                        )
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'messages' => array(
                                NotEmpty::IS_EMPTY => 'Ingresa tu password'
                            ),
                        ),
                    ),
                )
            ));

            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

}
