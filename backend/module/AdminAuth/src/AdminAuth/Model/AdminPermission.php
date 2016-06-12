<?php

namespace AdminAuth\Model;

use Base\BaseModel;


class AdminPermission extends BaseModel {

    public $name;
    public $status;
    public $label;

    public function __construct(){
        parent::__construct('web_system_auth_permissions');
    }
}