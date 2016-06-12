<?php

namespace AdminAuth\Model;

use Base\BaseModel;


class AdminUser extends BaseModel {

    public $first_name;
    public $last_name;
    public $email;
    public $username;
    public $password;
    public $status;
    public $is_superuser;
    public $last_login;
    public $role_id;

    public function __construct()
    {
        parent::__construct('web_system_auth_user');
    }
}