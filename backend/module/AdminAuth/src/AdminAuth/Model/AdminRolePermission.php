<?php

namespace AdminAuth\Model;

use Base\BaseModel;


class AdminRolePermission extends BaseModel{

    public $role_id;
    public $permission_id;

    public function __construct(){
        parent::__construct('web_system_auth_role_persmissions',false);
    }
}