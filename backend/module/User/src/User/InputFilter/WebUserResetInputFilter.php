<?php
/**
 * Created by PhpStorm.
 * User: Flash
 * Date: 13/01/15
 * Time: 03:23 PM
 */

namespace User\InputFilter;


use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\Identical;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;

class WebUserResetInputFilter implements  InputFilterAwareInterface{
    protected $inputFilter;

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {

            $this->inputFilter = new InputFilter();

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
        }
        return $this->inputFilter;
    }

} 