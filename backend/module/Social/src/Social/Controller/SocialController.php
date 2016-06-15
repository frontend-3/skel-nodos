<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Social\Controller;

use Base\BaseController;


function get_data_fb($url, $params){
    $url .= '?' . http_build_query($params);
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        return file_get_contents($url);
    }else
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $data;
    }
}

class SocialController extends BaseController {

    public function loginFacebookAction(){

    }

}
