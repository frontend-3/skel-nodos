<?php
/**
 * Created by PhpStorm.
 * User: Flash
 * Date: 20/11/14
 * Time: 11:32 AM
 */

namespace Contact\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Captcha\Image;


class WebContactForm extends Form{

    public function __construct($show_captcha = false,$url=''){
        parent::__construct('Contact');
        $this->setAttribute('autocomplete','off');
        $e = new Element\Text('full_name');
        $this->add($e);

        $e = new Element\Text('full_name');
        $this->add($e);

        $e = new Element\Text('email');
        $this->add($e);

        $e = new Element\Textarea('comment');
        $this->add($e);

        $e = new Element\Select('category_id');
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

}
