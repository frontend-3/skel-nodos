<?php

namespace User\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Captcha\Image;

class WebUserLoginForm extends Form{
    public function __construct($show_captcha = false,$url='') {
        parent::__construct('Login');
        $this->setAttribute('autocomplete','off');
        $e = new Element\Text('login_email');
        $this->add($e);

        $e = new Element\Password('login_password');
        $e->setAttribute('maxlength','20');
        $this->add($e);

        $e = new Element\Csrf('login_csrf_token');
        $e->setAttributes(array(
            'id' => 'login_csrf_token'
        ));
        $e->setOptions(array(
            'csrf_options' => array(
                'timeout' => 3600
            )
        ));
        $this->add($e);

        if ($show_captcha) {
            $captcha = new Image(array(
                'expiration' => '3600',
                'wordlen' => '6',
                'font' => 'public/fonts/arial.ttf',
                'fontSize' => '30',
                'imgDir' => 'public/captcha',
                'width'=>300,
                'height'=>100,
                'imgUrl' => $url,
                'messages' => array(
                    'badCaptcha' => 'Captcha incorrecto'
                )
            ));
            $captcha->setExpiration(30);
            $captcha->setGcFreq(1);

            $e = new Element\Captcha('captcha');
            $e->setAttribute('id', 'captcha');
            $e->setOptions(array('captcha' => $captcha));
            $this->add($e);
        }
        $e = new Element\Submit('Register');
        $this->add($e);
    }

    public function addCaptcha($url){
        if(!$this->has('captcha')){
            $captcha = new Image(array(
                'expiration' => '3600',
                'wordlen' => '6',
                'font' => 'public/fonts/arial.ttf',
                'fontSize' => '30',
                'imgDir' => 'public/captcha',
                'width'=>300,
                'height'=>100,
                'imgUrl' => $url,
                'messages' => array(
                    'badCaptcha' => 'Captcha incorrecto'
                )
            ));
            $captcha->setExpiration(30);
            $captcha->setGcFreq(1);

            $e = new Element\Captcha('captcha');
            $e->setAttribute('id', 'captcha');
            $e->setOptions(array('captcha' => $captcha));
            $this->add($e);
        }

    }

}
