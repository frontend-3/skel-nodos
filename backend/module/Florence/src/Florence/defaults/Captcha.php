<?php
$captcha_defaults = array(
  'id'               => $name,
  'image_expiration' => 30,
  'image_gcfreq'     => 1,
  'image'            => '',
  'required'         => true,
);

reverse_merge($this->elements[$name], $captcha_defaults);

$captcha_image_defaults = array(
    'expiration' => '3600',
    'wordlen'    => '6',
    'font'       => 'public/fonts/arial.ttf',
    'fontSize'   => '30',
    'width'      => 300,
    'height'     => 100,
    'imgDir'     => 'public/captcha',
    'imgUrl'     => sprintf('%s/public/%s/', ROOT_PATH, 'captcha'),
    'messages'   => array(
        'badCaptcha' => 'Captcha incorrecto'
    ),
);

reverse_merge($this->elements[$name]['image'], $captcha_image_defaults);

?>
