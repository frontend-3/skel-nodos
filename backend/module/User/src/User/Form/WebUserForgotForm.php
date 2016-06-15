<?php

/**
 * Created by PhpStorm.
 * User: Flash
 * Date: 27/06/14
 * Time: 02:34 PM
 */

namespace User\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Captcha\Image;

class WebUserForgotForm extends Form {

    public function __construct($show_captcha = false,$url='') {
        parent::__construct('UserForgotPassword');

        $e = new Element\Text('email');
        $e->setAttribute('id', 'email');
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

        if ($show_captcha) {
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
        $e = new Element\Submit('Enviar');
        $this->add($e);
    }

}
