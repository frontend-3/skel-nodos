<?php

/**
 * Created by PhpStorm.
 * User: Flash
 * Date: 27/06/14
 * Time: 02:34 PM
 */

namespace User\Form;

use Zend\Captcha\Image;
use Zend\Form\Form;
use Zend\Form\Element;

class WebUserResetForm extends Form {

    public function __construct($url) {
        parent::__construct('UserResetPassword');

        $e = new Element\Password('password');
        $this->add($e);

        $e = new Element\Password('confirm_password');
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
        $e = new Element\Submit('Enviar');
        $this->add($e);

        $captcha = new Image(array(
            'expiration' => '3600',
            'wordlen' => '6',
            'font' => 'public/fonts/arial.ttf',
            'fontSize' => '30',
            'imgDir' => 'public/captcha',
            'width' => 300,
            'height' => 100,
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
