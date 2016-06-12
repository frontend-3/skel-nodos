<?php
/**
 * Created by PhpStorm.
 * User: Flash
 * Date: 25/02/2015
 * Time: 11:17 AM
 */
namespace Social\Model;

use Base\Model\BaseModel;

class WebSocial extends BaseModel {

    public $id;
    public $uid;
    public $type;
    public $outh_token;
    public $user_id;
    public $response;
    public $expires;
    public function __construct(){
        parent::__construct('web_social');
    }
}