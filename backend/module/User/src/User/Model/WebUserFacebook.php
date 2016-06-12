<?php
/**
 * Created by PhpStorm.
 * User: Flash
 * Date: 30/12/14
 * Time: 12:03 PM
 */

namespace User\Model;

use Base\BaseModel;

class WebUserFacebook extends BaseModel{

    public $id;
    public $fb_id;
    public $full_name;
    public $oauth_token;
    public $expires;
    public $user_id;

    public function __construct(){
        parent::__construct('web_users_facebook');
    }

} 