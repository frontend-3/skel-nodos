<?php

namespace User\InputFilter;

use Zend\Db\Adapter\Adapter;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\Db\RecordExists;
use Zend\Validator\EmailAddress;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;

class WebUserLoginInputFilter implements InputFilterAwareInterface{
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
                'name' => 'login_email',
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
                                StringLength::TOO_SHORT => '',
                                StringLength::TOO_LONG => ''
                            ),
                        ),
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                NotEmpty::IS_EMPTY => ''
                            ),
                        ),
                    ),
                    array(
                        'name' => 'EmailAddress',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'messages' => array(
                                EmailAddress::INVALID_FORMAT => '',
                                EmailAddress::INVALID_HOSTNAME=>'',
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

            $this->inputFilter->add(array(
                'name' => 'login_password',
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
                            'max' => 20,
                            'messages' => array(
                                StringLength::TOO_SHORT => '',
                                StringLength::TOO_LONG => ''
                            ),
                        ),
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                NotEmpty::IS_EMPTY => ''
                            ),
                        ),
                    ),
                ),
            ));
        }
        return $this->inputFilter;
    }

}
