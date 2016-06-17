<?php

namespace AdminAuth\InputFilter;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;


class AdminLoginInputFilter implements InputFilterAwareInterface{

    protected $inputFilter;

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

    public function getInputFilter() {

        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'username',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'messages' => array(
                                NotEmpty::IS_EMPTY => 'Ingresa tu username'
                            ),
                        ),
                    ),
                )
            ));

            $inputFilter->add(array(
                'name' => 'password',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'min' => 6,
                            'messages' => array(
                                StringLength::TOO_SHORT => 'Ingresa tu password'
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
